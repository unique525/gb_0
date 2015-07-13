<style>
    .am-slider-c1 .am-slider-desc {padding:2px}
    .am-slider-c1 .am-control-nav li a.am-active {background-color: #FB5517;}
</style>
<div data-am-widget="slider" class="am-slider am-slider-c1" data-am-slider='{&quot;directionNav&quot;:false}' data-am-flexslider="{&quot;controlNav&quot;:true,smoothHeight:true}">
    <ul class="am-slides">
        <icms id="document_news_pic_slider_type_0" type="list">
            <item>
                <![CDATA[
                <li>
                    <img class="document_news_pic_slider_item" src="{f_UploadFileWatermarkPath1}" idvalue="{f_UploadFilePath}">
                    <div class="am-slider-desc">{f_UploadFileTitle}</div>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".document_news_pic_slider_item").each(function(){
           if($(this).attr("src")==""){
               $(this).attr("src",$(this).attr("idvalue"));
           }
            $(this).attr("idvalue","");
        });
    }
</script>