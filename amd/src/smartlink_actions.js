import $ from 'jquery';
import Ajax from 'core/ajax';
import * as Str from 'core/str';

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

        $("[data-dismiss='modal']").on("click", function () {
            $('form[name="custom-prompt-form"]')[0].reset();
        });

        // Click outside modal
        $(document).click(async function (e) {
            if (e.target.id === "ownPromptModal") {
                var confimationMsg = await Str.get_string("prompt_modal_close_warning", "smartlink");
                if (confirm(confimationMsg)) {
                    $(".custom-prompt-modal").modal("toggle");
                }
            } else if (e.target.id === "responseModal") {
                var confimationMsg = await Str.get_string("response_modal_close_warning", "smartlink");
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
        $('#ownPromptModal[data-moduleid="'+this.moduleid+'"] .submit-own-prompt-btn').click(function (e) {
            e.preventDefault();
            let prompt = $('#ownPromptModal[data-moduleid="'+this.moduleid+'"] textarea[name="prompt"]').val();
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
        let responseObj = JSON.parse(response);
        let modal = $('#ownPromptModal[data-moduleid="'+this.moduleid+'"]');

        if (modal.is(':visible')) {
            modal.modal('toggle');
        }

        // Might contain errors anyway
        if (responseObj.success == false) {
            alert(responseObj.message);
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
    handleFailure(response) {
        $('.smartlink[data-id="'+this.moduleid+'"] .spinner').addClass("d-none");
        var responseObj = JSON.parse(response);
        alert(responseObj.message);
    }
}

export const init = (courseid, instanceid, moduleid) => {
    return new SmartLinkActions(courseid, instanceid, moduleid);
};
