
function submitForm(continueCreate) {
    if ($('#f_CustomFormSubject').val() == '') {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("请输入表单名称");
    } else {
        if (continueCreate == 1) {
            $("#CloseTab").val("0");
        } else {
            $("#CloseTab").val("1");
        }
        $('#main_form').submit();
    }
}
