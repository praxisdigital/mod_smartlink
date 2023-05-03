import $ from 'jquery';
import * as Str from 'core/str';

class AdminSettings {

    constructor(prompts, langs) {
        this.existingPrompts = Object.values(prompts);
        this.langs = Object.values(langs);
        this.init();
    }

    init() {

        // Handle modal outside click confirmation
        $(document).click(async function (e) {
            if (e.target.id === "newPromptModal") {
                var confimationMsg = await Str.get_string("prompt_modal_close_warning", "smartlink");
                if (confirm(confimationMsg)) {
                    $(".modal").modal("toggle");
                }
            }
        });

        // Handle lang selection change
        $(".lang-selection").click(function (e) {
            e.preventDefault();
            var content = $(this).html();
            var attrVal = $(this).attr("data-id");
            $("input[name='language']").val(attrVal);
            $("#lang-selection-text").html(content);
        });

        // Handle status selection changes
        $(".status-selection").click(function (e) {
            e.preventDefault();
            var content = $(this).html();
            var attrVal = $(this).attr("data-id");
            $("input[name='active']").val(attrVal);
            $("#status-selection-text").html(content);
        });

        $('form[name="prompt-form"]').submit(function (e) {
            e.preventDefault();
            var promptVal = $("textarea[name='prompt']").val();
            if (promptVal.indexOf("{url}") >= 0) {
                $('form[name="prompt-form"]')[0].submit();
            } else {
                $("textarea[name='prompt']").addClass("is-invalid");
            }
        });

        $("#add-prompt-btn").click(
            async function () {
                var addStr = await Str.get_string("add", "core");
                $("#add-update-btn").html(addStr);
                $("textarea[name='prompt']").removeClass("is-invalid");
                var status = await Str.get_string("active", "core");
                $("input[name='id']").val(0);
                $("input[name='language']").val("en");
                $("input[name='active']").val(1);
                $("input[name='action']").val("add_prompt");
                //set default values
                var initLang = this.langs[0];
                $("input[name='language']").val(initLang.code);
                $("#lang-selection-text").html(initLang.name);
                $("input[name='active']").val(1);
                $("#status-selection-text").html(status);
                $(".modal").modal("toggle");
            }.bind(this)
        );

        $(".modal").on("hidden.bs.modal", function () {
            $(this).find("form")[0].reset();
            $(".prompt-edit-warning").css("display", "none");
        });

        $("[data-dismiss='modal']").on("click", function () {
            $('form[name="prompt-form"]')[0].reset();
            $(".prompt-edit-warning").css("display", "none");
        });

        $(".prompt-edit-icon").click(
            async function (e) {
                e.preventDefault();
                var attrVal = $(e.currentTarget).attr("data-id");
                var promptSetting = this.existingPrompts.find((prompt) => prompt.id == attrVal);

                if (promptSetting) {
                    $(".prompt-edit-warning").css("display", "block");
                    var updateStr = await Str.get_string("update", "core");
                    $("#add-update-btn").html(updateStr);
                    $("textarea[name='prompt']").removeClass("is-invalid");
                    $("input[name='id']").val(promptSetting.id);
                    $("input[name='active']").val(promptSetting.active);
                    $("input[name='action']").val("add_prompt");
                    $("textarea[name='prompt']").val(promptSetting.prompt);
                    $("textarea[name='description']").val(promptSetting.description);
                    var langSelected = this.langs.find((ln) => ln.code == promptSetting.language);
                    $("input[name='language']").val(langSelected.code);
                    $("#lang-selection-text").html(langSelected.name);
                    $("#status-selection-text").html(promptSetting.status_string);
                    $(".modal").modal("toggle");
                }
            }.bind(this)
        );

    }

}

export const init = (prompts, langs) => {
    return new AdminSettings(prompts, langs);
};
