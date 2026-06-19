import $ from 'jquery';
import Ajax from 'core/ajax';
import * as Str from 'core/str';
import Templates from "core/templates";

class SmartLinkActions {

    constructor(courseid, instanceid, moduleid) {
        this.prompts = [];
        this.courseid = courseid;
        this.instanceid = instanceid;
        this.moduleid = moduleid;
        this.contextid = 1;
        this.init();
    }

    getPrompts() {
        Ajax.call([
            {
                methodname: "mod_smartlink_get_available_prompts",
                args: {},
                done: this.handlePrompts.bind(this),
                fail: this.handleFailure.bind(this),
            },
        ]);
    }

    handlePrompts(response) {
        let responseData = JSON.parse(response);
        let prompts = responseData.data || {};
        this.prompts = Object.values(prompts);
    }

    init() {
        this.getPrompts();

        $(".modal").on("hidden.bs.modal", function () {
            $('form[name="custom-prompt-form"]')[0].reset();
        });

        $("[data-bs-dismiss='modal']").on("click", function () {
            $('form[name="custom-prompt-form"]')[0].reset();
        });

        // Click outside modal
        $(document).click(async function (e) {
            if (e.target.id.includes("ownPromptModal-")) {
                var confimationMsg = await Str.get_string("prompt_modal_close_warning", "mod_smartlink");
                if (confirm(confimationMsg)) {
                    $(".custom-prompt-modal").modal("toggle");
                }
            } else if (e.target.id === "responseModal") {
                var confimationMsg = await Str.get_string("response_modal_close_warning", "mod_smartlink");
                if (confirm(confimationMsg)) {
                    $(".response-modal").modal("toggle");
                }
            }
        });

        // Prompt from list
        $('.smartlink[data-id="'+this.moduleid+'"] .run-prompt-btn').click(function (e) {
            e.preventDefault();
            let promptId = $(e.target).data('id');
            let promptSetting = this.prompts.find((prompt) => prompt.id == promptId);
            if (promptSetting && !this.siblingsLoading()) {
                this.getAiResponse({
                    promptid: promptSetting.id,
                    courseid: this.courseid,
                    instanceid: this.instanceid
                });
            }
        }.bind(this));

        // Prompt from input
        $('#ownPromptModal-' + this.moduleid + ' .submit-own-prompt-btn').click(function (e) {
            e.preventDefault();
            let prompt = $('#ownPromptModal-' + this.moduleid + ' textarea[name="prompt"]').val();
            if (prompt && !this.siblingsLoading()) {
                this.getAiResponse({
                    prompt: prompt,
                    courseid: this.courseid,
                    instanceid: this.instanceid
                });
            }
        }.bind(this));
    }

    siblingsLoading() {
        let loading = false;
        document.querySelectorAll('.smartlink .spinner').forEach(function(spinner) {
            if (!spinner.classList.contains('d-none')) {
                loading = true;
            }
        });
        return loading;
    }

    getAiResponse(promptdata) {
        $('.smartlink[data-id="'+this.moduleid+'"] .spinner').removeClass("d-none");
        Ajax.call([
            {
                methodname: "mod_smartlink_prompt_openai",
                args: { contextid: this.contextid, jsondata: JSON.stringify(promptdata) },
                done: this.handleResponse.bind(this),
                fail: this.handleFailure.bind(this),
            },
        ]);
    }

    // On a succesful response
    handleResponse(response) {
        $('.smartlink[data-id="'+this.moduleid+'"] .spinner').addClass("d-none");
        let modal = $('#ownPromptModal-' + this.moduleid);
        let responseObj = JSON.parse(response);

        if (modal.is(':visible')) {
            modal.modal('toggle');
        }

        // Might contain errors anyway
        if (responseObj.success == false) {
            this.handleFailure(response)
        } else {
            let data = responseObj.data;
            let result = data.result || 'No response from AI';
            $(".prompt-desc").html(data.description || '');
            $(".prompt-text").html(data.prompt_text || '');
            $(".ai-response").html(result.replace(/\n/g, '<br/>'));
            $("#responseModal").modal("toggle");
        }
    }

    // Failure
    async handleFailure(response) {
        $('.smartlink[data-id="'+this.moduleid+'"] .spinner').addClass("d-none")

        try{

            let responseObj;
            try {
                responseObj = JSON.parse(response);
            } catch (e) {
                responseObj = null;
            }
            if (!responseObj || typeof responseObj !== 'object') {
                const err_msg = await Str.get_string("unknown_error", "mod_smartlink");
                responseObj = { message: err_msg };
            }

            let template_context = {
                err_msg:responseObj.message
            };

            const { html, js } = await Templates.renderForPromise('mod_smartlink/exception', template_context);
            // Close own-prompt modal if open
            const ownModal = $('#ownPromptModal-' + this.moduleid);
            if (ownModal.is(':visible')) {
                ownModal.modal('hide');
            }
            // Remove any existing #exceptionModal (success or previous exception) to avoid id collision
            $('#exceptionModal').remove();
            // Append new exception modal to body and run its JS
            Templates.appendNodeContents('body', html, js);
            // Show it
            $('#exceptionModal').modal('show');

        }
        catch(err){
            window.console.error(err);
        }
    }
}

export const init = (courseid, instanceid, moduleid) => {
    return new SmartLinkActions(courseid, instanceid, moduleid);
};
