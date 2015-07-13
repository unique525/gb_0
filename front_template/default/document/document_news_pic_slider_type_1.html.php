
<div class="am-slider am-slider-default" data-am-flexslider="{smoothHeight:true,animationLoop:false}">
    <ul class="am-slides">
        <icms id="document_news_pic_slider_type_0" type="list">
            <item>
                <![CDATA[
                <li class="document_news_pic_slider"><table align="center" height="100%" width="100%" >
                        <tr><td ><img class="document_news_pic_slider_img" src="{f_UploadFileWatermarkPath1}" idvalue="{f_UploadFilePath}"/></td></tr>
                    </table></li>
                ]]>
            </item>
        </icms>
    </ul>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".document_news_pic_slider_img").each(function(){
            if($(this).attr("src")==""){
                $(this).attr("src",$(this).attr("idvalue"));
            }
            $(this).attr("idvalue","");
        });
    }
</script>