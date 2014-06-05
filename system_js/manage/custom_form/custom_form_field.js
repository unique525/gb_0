/**
 * Created by 525 on 14-5-27.
 */
$("document").ready(function(){

    $("#btn_create").click(function(event) {
        event.preventDefault();
        parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form&m=create' + '&channel_id=' +  parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-新增字段';
        parent.addTab();
    });
});