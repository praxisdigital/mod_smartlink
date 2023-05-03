import $ from 'jquery';
import Ajax from 'core/ajax';
import * as Str from 'core/str';

class SmartLinkActions {

    constructor(prompts, courseid, instanceid) {
        this.prompts = Object.values(prompts);
        this.courseid = courseid;
        this.instanceid = instanceid;
        this.contextid = 1;
        this.init();
    }

    init() {
        $(".modal").on("hidden.bs.modal", function () {
            $('form[name="custom-prompt-form"]')[0].reset();
        });

        $("[data-dismiss='modal']").on("click", function () {
            $('form[name="custom-prompt-form"]')[0].reset();
        });

        //handle modal outside click confirmation
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

        $(".run-prompt-btn").click(
            function (e) {
                e.preventDefault();
                var attrVal = $(e.currentTarget).attr("data-id");
                var promptSetting = this.prompts.find((prompt) => prompt.id == attrVal);
                if (promptSetting) {
                    this.getAiResponse({ promptid: promptSetting.id, courseid: this.courseid, instanceid: this.instanceid });
                }
            }.bind(this)
        );
        $(".submit-own-prompt-btn").click(
            function (e) {
                e.preventDefault();
                var promptVal = $("textarea[name='prompt']").val();
                this.getAiResponse({ prompt: promptVal, courseid: this.courseid, instanceid: this.instanceid });
            }.bind(this)
        );
    }

    getAiResponse(promptdata) {
        $("#loader").removeClass("d-none");
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
        $("#loader").addClass("d-none");
        var responseObj = JSON.parse(response);

        if ($("#ownPromptModal").is(":visible")) {
            $("#ownPromptModal").modal("toggle");
        }

        // Might contain errors anyway
        if (responseObj.success == false) {
            alert(responseObj.message);
        } else {
            var data = responseObj.data;
            $(".prompt-desc").html(data.description);
            $(".prompt-text").html(data.prompt_text);
            $(".ai-response").html(data.result.replace(/\n/g, "<br/>"));
            $("#responseModal").modal("toggle");
        }
    }

    // Failure
    handleFailure(response) {
        $("#loader").addClass("d-none");
        var responseObj = JSON.parse(response);
        alert(responseObj.message);
    }
}

export const init = (prompts, courseid, instanceid) => {
    return new SmartLinkActions(prompts, courseid, instanceid);
};
