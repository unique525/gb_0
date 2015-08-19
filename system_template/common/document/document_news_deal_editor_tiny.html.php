<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>
	{common_head}
	<script src="/system_js/tiny_mce/tiny_mce_src.js"></script>
	<script src="/system_js/tiny_mce/editor.js"></script>
	<script type="text/javascript" src="/system_js/color_picker.js"></script>
	<script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
	<script type="text/javascript" src="/system_js/upload_file.js"></script>
	<script type="text/javascript" src="/system_js/manage/document_news/document_news_pic.js"></script>

	<link rel="stylesheet" href="/system_js/plupload-2.1.2/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
	<link rel="stylesheet" href="/system_js/plupload-2.1.2/js/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" />
	<script type="text/javascript" src="/system_js/plupload-2.1.2/js/plupload.full.min.js"></script>
	<script type="text/javascript" src="/system_js/plupload-2.1.2/js/plupload.dev.js"></script>
	<script type="text/javascript" src="/system_js/plupload-2.1.2/js/i18n/zh_CN.js"></script>
	<script type="text/javascript" src="/system_js/plupload-2.1.2/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="/system_js/plupload-2.1.2/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
	<link rel="stylesheet" href="/system_js/fancy_box/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="/system_js/fancy_box/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript">

		//切换到xheditor编辑器
		function changeEditor(){
			setcookie("editor", "XH");

			var document_news_id = Request["document_news_id"];
			var tab_index        = Request["tab_index"];
			var p                = Request["p"];

			window.location.href = "/default.php"
					+"?secu=manage"
					+ "&mod=document_news"
					+ "&m=modify"
					+ "&editor=xh"
					+ "&document_news_id=" + document_news_id
					+ "&tab_index=" + tab_index
					+ "&p=" + p ;
		}


	</script>

	<style>
		.plupload_scroll {
			background-color: #E6E8EC;
			}
		.plupload_filelist_footer{
			position:relative;
			}
		.plupload_container{
			min-height: 380px;
			}




		/***** 图片管理css  *****/
		.li_pic_img_item{
			width:276px;
			float: left;
			margin: 10px;
			box-shadow: 0 0 5px #666;
			padding-right: 4px;
			background: none repeat scroll 0 0 #EFEFEF;
			position: relative;
			}
		.notice {width:100%;height:100%;position: absolute;background-color: rgba(3, 3, 3, 0.7);z-index: 5;display: none}
		.pic_img_container {width:280px;height:190px;background-color: rgb(253, 253, 253)}
		.pic_img_container img{max-width:280px;max-height:190px;display: block}
		.pic_img_title{padding:3px 5px;width:266px;height:22px;position:relative}
		.pic_img_title input{width:265px;height:15px;position: absolute;margin-top:3px;z-index: 10}
		.pic_img_state{padding:3px 5px;position: relative}
		.pic_img_state span{float:left;}
		.pic_img_state img{vertical-align:middle;cursor: pointer}
		.btn_update_title{float:right;cursor: pointer;display: none;padding:0 5px;right:0;top: 3px;background: #eee;z-index: 10;position: absolute}
	</style>
</head>
<body>

{common_body_deal}
<div id="dialog_abstract_box" title="选择来源" style="">
	<div style="text-align:right;"><img id="btn_close_abstract_box" alt="关闭" title="关闭" src="/system_template/{template_name}/images/manage/close3.gif"/></div>
	<div id="dialog_abstract_content">

	</div>
</div>
<div class="div_deal">
	<form id="mainForm" enctype="multipart/form-data" method="post">
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="spe_line" height="40" align="right">
					<input class="btn" value="切换到手机编辑器" type="button" onclick="()" />
					<input class="btn" value="切换到XH编辑器" type="button" onclick="changeEditor()" />
					<input class="btn" value="确认并关闭"      type="button" onclick="submitForm(1)"/>
					<input class="btn" value="确认并编辑"      type="button" onclick="submitForm(2)"/>
					<input class="btn" value="确认并继续"      type="button" onclick="submitForm(0)"/>
					<input class="btn" value="取 消" type="button" onclick="closeTab()"/>
				</td>
			</tr>
		</table>
		<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<div id="tabs" style="margin-left:4px;">
						<ul>
							<li><a href="#tabs-1">文档内容</a></li>
							<li><a href="#tabs-2" style="display:none">文档参数</a></li>
							<li><a href="#tabs-3">批量上传</a></li>
							<li><a href="#tabs-4">其他属性</a></li>
							<li><a href="#tabs-5">图片管理</a></li>
						</ul>
						<div id="tabs-1">
							<div style="text-align: left;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<!--标题-->
										<td class="spe_line" style=" width: 60px;text-align: left;"><label for="f_DocumentNewsTitle">标题：</label></td>
										<td class="spe_line" >
											<input type="text" class="iColorPicker input_box input_title" id="f_DocumentNewsTitle"
											       name="f_DocumentNewsTitle" value="{DocumentNewsTitle}"
											       style="width:90%;font-size:14px; background-color: #ffffff;" maxlength="200"/>
											<input type="hidden" id="f_DocumentNewsTitleColor" name="f_DocumentNewsTitleColor"
											       value="{DocumentNewsTitleColor}"/>
											<input type="hidden" id="f_DocumentNewsTitleBold" name="f_DocumentNewsTitleBold"
											       value="{DocumentNewsTitleBold}"/>
											<input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}"/>
											<input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}"/>
											<input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}"/>
										</td>
										<td class="spe_line" style="width:140px">
											<input type="checkbox" id="cbTitleBold"/> <label for="cbTitleBold">加粗</label>
											<input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
											<span id="length_f_DocumentNewsTitle"></span>
										</td>

										<!--短标题-->
										<td class="spe_line" style="width: 60px;height:35px;text-align: right;"><label
													for="f_DocumentNewsShortTitle">短标题：</label></td>
										<td class="spe_line" style="text-align: left"><input type="text" class="input_box input_title"
										                                                     id="f_DocumentNewsShortTitle"
										                                                     name="f_DocumentNewsShortTitle"
										                                                     value="{DocumentNewsShortTitle}"
										                                                     style=" width: 90%;font-size:14px;" maxlength="100"/>
										</td>
										<td class="spe_line" style="width:140px">
											<span id="length_f_DocumentNewsShortTitle"></span>
										</td>
									</tr>
									<tr>

										<!--副标题-->
										<td class="spe_line" style="width:60px;height:35px;text-align: left;"><label for="f_DocumentNewsSubTitle">副标题：</label>
										</td>
										<td class="spe_line" style="text-align: left"><input type="text" class="input_box input_title"
										                                                     id="f_DocumentNewsSubTitle"
										                                                     name="f_DocumentNewsSubTitle"
										                                                     value="{DocumentNewsSubTitle}"
										                                                     style=" width: 90%;font-size:14px;" maxlength="100"/>
										</td>
										<td class="spe_line" style="width:140px">
											<span id="length_f_DocumentNewsSubTitle"></span>
										</td>
										<!--引题-->
										<td class="spe_line" style="width:60px;height:35px;text-align: right;"><label
													for="f_DocumentNewsCiteTitle">引题：</label></td>
										<td class="spe_line" style="text-align: left"><input type="text" class="input_box input_title"
										                                                     id="f_DocumentNewsCiteTitle"
										                                                     name="f_DocumentNewsCiteTitle"
										                                                     value="{DocumentNewsCiteTitle}"
										                                                     style=" width: 90%;font-size:14px;" maxlength="100"/>
										</td>
										<td class="spe_line" style="width:140px">
											<span id="length_f_DocumentNewsCiteTitle"></span>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td class="" style="width:60px;text-align: left;"><label
													for="f_DocumentNewsIntro">摘要：</label><br/>
											<input type="button" class="btn4" value="编 写"
											       onclick='showModalDialog("/system_js/manage/document_news/edit_abstract.html", window, "dialogWidth:850px;dialogHeight:400px;help:no;scroll:no;status:no");'/>&nbsp;
										</td>
										<td class="" style="width:40%;text-align: left"><textarea class="input_box input_title" id="f_DocumentNewsIntro"
										                                                          name="f_DocumentNewsIntro"
										                                                          style="width: 90%; height: 90px;font-size:14px;">{DocumentNewsIntro}</textarea>
											<div id="length_f_DocumentNewsIntro" style="line-height:20px"></div>
										</td>

										<td>

											<table width="99%" border="0" cellspacing="0" cellpadding="0">

												<tr>
													<td class="" style="width:60px;height:30px;text-align: right;">题图1：</td>
													<td class="" style="text-align: left">
														<input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
														       style="width:auto;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic1" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>
														<a class="fancybox fancybox.iframe" href="/default.php?secu=manage&mod=upload_file&m=create_cut_image&upload_file_id={TitlePic1UploadFileId}&source_type=1">[制作截图]</a>
														<span id="preview_title_pic_cut1" class="show_title_pic_cut" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览截图]</span>
													</td>
												</tr>
												<tr>
													<td class="" style="width:60px;height:30px;text-align: right;">题图2：</td>
													<td class="" style="text-align: left">
														<input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box"
														       style="width:auto; background: #ffffff; margin-top: 3px;"/> <span id="preview_title_pic2"
														                                                                         class="show_title_pic"
														                                                                         idvalue="{TitlePic2UploadFileId}"
														                                                                         style="cursor:pointer">[预览]</span>
														<a class="fancybox fancybox.iframe" href="/default.php?secu=manage&mod=upload_file&m=create_cut_image&upload_file_id={TitlePic2UploadFileId}&source_type=1">[制作截图]</a>
														<span id="preview_title_pic_cut2" class="show_title_pic_cut" idvalue="{TitlePic2UploadFileId}" style="cursor:pointer">[预览截图]</span>
													</td>
												</tr>
												<tr>
													<td class="" style="width:60px;height:30px;text-align: right;">题图3：</td>
													<td class="" style="text-align: left">
														<input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box"
														       style="width:auto; background: #ffffff; margin-top: 3px;"/> <span id="preview_title_pic3"
														                                                                         class="show_title_pic"
														                                                                         idvalue="{TitlePic3UploadFileId}"
														                                                                         style="cursor:pointer">[预览]</span>
														<a class="fancybox fancybox.iframe" href="/default.php?secu=manage&mod=upload_file&m=create_cut_image&upload_file_id={TitlePic3UploadFileId}&source_type=1">[制作截图]</a>
														<span id="preview_title_pic_cut3" class="show_title_pic_cut" idvalue="{TitlePic3UploadFileId}" style="cursor:pointer">[预览截图]</span>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td class="spe_line" style="width:120px;height:35px;text-align: left;"><label
													for="f_DirectUrl">直接转向网址：</label></td>
										<td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_DirectUrl"
										                                                     name="f_DirectUrl" value="{DirectUrl}"
										                                                     style=" width: 70%;font-size:14px;" maxlength="200"/>
										</td>
									</tr>
								</table>
							</div>
							<div style=" margin-top:3px;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="75%"><label for="f_DocumentNewsContent"></label><textarea class="mceEditor"
										                                                                     id="f_DocumentNewsContent"
										                                                                     name="f_DocumentNewsContent"
										                                                                     style=" width: 100%;">{DocumentNewsContent}</textarea>
										</td>
										<td style="vertical-align:top;">
											<table width="100%" border="0" cellspacing="0" cellpadding="0"
											       style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
												<tr>
													<td style="width:74px;height:35px;text-align: right;"><label for="f_SourceName">来源：</label>
													</td>
													<td style="text-align: left; line-height:180%;">
														<input type="text" class="input_box" id="f_SourceName" name="f_SourceName"
														       value="{SourceName}" style=" width:60%;font-size:14px; margin-top: 4px;"
														       maxlength="50"/>&nbsp;<span id="btn_select_source">[选择来源]</span>
													</td>
												</tr>
												<tr>
													<td class="spe_line" style="width:74px;height:35px;text-align: right;">常用来源：</td>
													<td class="spe_line" style="text-align: left; line-height:180%;">
														<icms id="source_common_list" type="list">
															<item>
																<![CDATA[<span class="btnSetSourceName">{f_SourceName}</span><br/>]]>
															</item>
														</icms>
													</td>
												</tr>
												<tr>
													<td class="spe_line" style="width:74px;height:35px;text-align: right;"><label
																for="f_Author">作者：</label></td>
													<td class="spe_line" style="text-align: left">
														<input type="text" class="input_box" id="f_Author" name="f_Author" value="{Author}"
														       style="width:95%;font-size:14px;" maxlength="50"/>
													</td>
												</tr>
												<tr>
													<td class="spe_line" style="width:74px;height:35px;text-align: right;"><label
																for="f_DocumentNewsMainTag">主关键词：</label></td>
													<td class="spe_line" style="text-align: left">
														<input type="text" class="input_box" id="f_DocumentNewsMainTag"
														       name="f_DocumentNewsMainTag" value="{DocumentNewsMainTag}"
														       style="width:95%;font-size:14px;" maxlength="100"/>
													</td>
												</tr>
												<tr>
													<td class="spe_line" style="width:74px;height:35px;text-align: right;"><label
																for="f_DocumentNewsTag">关键词：</label></td>
													<td class="spe_line" style="text-align: left">
														<input type="text" class="input_box" id="f_DocumentNewsTag" name="f_DocumentNewsTag"
														       value="{DocumentNewsTag}" style="width:75%;font-size:14px;" maxlength="200"/>
														<input id="btn_extract" value="抽取" type="button" onclick="DocumentNewsTagPulling()">
													</td>
												</tr>
												<tr>
													<td class="spe_line" style="height:35px;text-align: right;">加前缀：</td>
													<td class="spe_line" align="left" style="line-height:20px;">
														<icms id="document_pre_content" type="list">
															<item>
																<![CDATA[
                                        <span style="cursor: pointer;" class="btn_add_pre_content"
                                              title="{f_DocumentPreContent}">{f_DocumentPreContent}</span><br/>
																]]>
															</item>
														</icms>
													</td>
												</tr>
												<tr>
													<td style="height:35px;">文件上传：</td>
													<td align="left">
														<input id="file_upload_to_content" name="file_upload_to_content" type="file"
														       class="input_box" size="7" style="width:60%; background: #ffffff;"/> <img
																id="loading" src="/system_template/common/images/loading1.gif"
																style="display:none;"/><input id="btnUploadToContent" type="button" value="上传"/>
													</td>
												</tr>
												<tr>
													<td style="height:35px;"><label for="cbAttachWatermark">附加水印：</label></td>
													<td align="left">
														<input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark" title="attach_water_mark"/> (只支持jpg或jpeg图片)
													</td>
												</tr>
												<tr>
													<td style="height:35px;"><label for="cbSaveRemoteImage">远程抓图：</label></td>
													<td align="left">
														<input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage" title="save_remote_image"/>
														(只支持jpg,jpeg,gif,png图片)
													</td>
												</tr>
												<tr>
													<td style="height:35px;"><label for="cbSaveRemoteImage">内容分页：</label></td>
													<td align="left">
														<input type="button" id="btnInsertContentPager" value="插入内容分页符" />
													</td>
												</tr>
												<tr>
													<td style="height:35px;text-align: right;"><label for="f_ShowIndex">推送首页：</label></td>
													<td style="text-align: left">
														<select id="f_ShowIndex" name="f_ShowIndex">
															<option value="0">不上首页</option>
															<option value="1">上首页 排序1</option>
															<option value="2">上首页 排序2</option>
															<option value="3">上首页 排序3</option>
															<option value="4">上首页 排序4</option>
															<option value="5">上首页 排序5</option>
															<option value="6">上首页 排序6</option>
															<option value="7">上首页 排序7</option>
															<option value="8">上首页 排序8</option>
															<option value="9">上首页 排序9</option>
															<option value="10">上首页 排序10</option>
															<option value="11">上首页 排序11</option>
															<option value="12">上首页 排序12</option>
															<option value="13">上首页 排序13</option>
															<option value="14">上首页 排序14</option>
															<option value="15">上首页 排序15</option>
															<option value="16">上首页 排序16</option>
															<option value="17">上首页 排序17</option>
															<option value="18">上首页 排序18</option>
															<option value="19">上首页 排序19</option>
															<option value="20">上首页 排序20</option>
														</select>
														{s_ShowIndex}(越大越靠前)
													</td>
												</tr>
												<tr>
													<td style="height:35px;text-align: right;"><label for="f_ShowInClient">客户端：</label></td>
													<td style="text-align: left">
														<select id="f_ShowInClient" name="f_ShowInClient">
															<option value="1">推送</option>
															<option value="0">不推送</option>
														</select>
														{s_ShowInClient}
													</td>
												</tr>
											</table>


										</td>
									</tr>
								</table>

								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>

										<td class="spe_line" style="width:80px;height:35px;text-align: right;">推荐级别：</td>
										<td class="spe_line" style="width:350px;text-align: left">
											<label>
												<input type="radio" name="f_RecLevel" value="0"/>
												0
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="1"/>
												1
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="2"/>
												2
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="3"/>
												3
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="4"/>
												4
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="5"/>
												5
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="6"/>
												6
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="7"/>
												7
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="8"/>
												8
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="9"/>
												9
											</label>
											<label>
												<input type="radio" name="f_RecLevel" value="10"/>
												10
											</label>
											{r_RecLevel}
										</td>
										<td class="spe_line" style="width:80px;height:35px;text-align: right;"><label for="f_Sort">排序数字：</label>
										</td>
										<td class="spe_line" style="width:240px;text-align: left"><input type="text" class="input_number" id="f_Sort"
										                                                                 name="f_Sort" value="{Sort}"
										                                                                 style=" width: 60px;font-size:14px;" maxlength="10"/>
											(越大越靠前)
										</td>
										<td class="spe_line" style="width:80px;height:35px;text-align: right;">是否热门：</td>
										<td class="spe_line" style="width:80px;text-align: left">
											<label>
												<input type="radio" name="f_IsHot" value="0"/>
												否
											</label>
											<label>
												<input type="radio" name="f_IsHot" value="1"/>
												是
											</label>
											{r_IsHot}
										</td>

									</tr>
								</table>
							</div>
						</div>
						<div id="tabs-2">

						</div>
						<div id="tabs-3">
							<div id="uploader">
								<p>您的浏览器不支持 Flash, SilverLight, Gears, BrowserPlus 或 HTML5，不能使用组图上传功能</p>
							</div>
							<div class="spe_line" style=" line-height: 30px;"><label for="f_ShowPicMethod">使用组图控件展示内容中的图片</label>
								<select id="f_ShowPicMethod" name="f_ShowPicMethod">
									<option value="0">关闭</option>
									<option value="1">开启</option>
								</select>
								{s_ShowPicMethod}
								&nbsp;&nbsp;&nbsp;&nbsp;
								<label for="batchAttachWatermark">附加水印：</label><input type="checkbox" id="batchAttachWatermark" name="batchAttachWatermark" title="batch_attach_water_mark"/> (只支持jpg或jpeg图片)
							</div>
						</div>
						<div id="tabs-4">

							<table width="99%" border="0" cellspacing="0" cellpadding="0">

								<tr>
								</tr>
								<tr>
								</tr>
								<tr>
								</tr>
								<tr>
									<td class="spe_line" style="width:200px;height:35px;text-align: right;">显示时间：</td>
									<td class="spe_line" style="text-align: left">
										<input type="text" class="input_box" id="f_ShowDate" name="f_ShowDate" value="{ShowDate}"
										       style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly"/><label
												for="f_ShowDate"> </label>
										<input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ShowHour"
										       name="f_ShowHour" value="{ShowHour}" maxlength="2"/><label for="f_ShowHour">:</label>
										<input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ShowMinute"
										       name="f_ShowMinute" value="{ShowMinute}" maxlength="2"/><label for="f_ShowMinute">:</label>
										<input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ShowSecond"
										       name="f_ShowSecond" value="{ShowSecond}" maxlength="2"/><label for="f_ShowSecond">
											(在文档中显示出来的时间，可任意设置)</label>
									</td>
								</tr>
								<tr>
									<td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_DocumentNewsType">新闻类型：</label>
									</td>
									<td class="spe_line" style="text-align: left">
										<select id="f_DocumentNewsType" name="f_DocumentNewsType">
											<option value="0">常规新闻</option>
										</select>{s_DocumentNewsType}
									</td>
								</tr>
								<tr>
									<td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_State">当前状态：</label>
									</td>
									<td class="spe_line" style="text-align: left">
										<select id="f_State" name="f_State">
											<option value="0">新稿</option>
											<option value="1">已编</option>
											<option value="2">返工</option>
											<option value="11">一审</option>
											<option value="12">二审</option>
											<option value="13">三审</option>
											<option value="14">终审</option>
											<option value="20">已否</option>
											<option value="30">已发</option>
											<option value="100">已删</option>
											<option value="127"></option>
										</select>
										{s_State}
									</td>
								</tr>
								<tr>
									<td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
												for="f_OpenComment">新闻评论：</label></td>
									<td class="spe_line" style="text-align: left">
										<select id="f_OpenComment" name="f_OpenComment">
											<option value="40">根据频道设置而定</option>
											<option value="20">允许但需要审核（先发后审）</option>
											<option value="10">允许但需要审核（先审后发）</option>
											<option value="30">自由评论</option>
											<option value="0">不允许</option>
										</select>
										{s_OpenComment}
									</td>
								</tr>
								<tr>
									<td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
												for="f_ClosePosition">心情表态：</label></td>
									<td class="spe_line" style="text-align: left">
										<select id="f_ClosePosition" name="f_ClosePosition">
											<option value="0">开启</option>
											<option value="1">关闭</option>
										</select>
										{s_ClosePosition}
									</td>
								</tr>
								<!-- 加点击 -->
								<tr>
									<td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
												for="f_VirtualHit">初始点击数：</label></td>
									<td class="spe_line" style="text-align: left">
										<input type="text" class="input_box" id="f_VirtualHit" name="f_VirtualHit"
										       value="{Hit}" style="width:100px;font-size:14px;" maxlength="200" />
										<script type="text/javascript">
											$(function () {
												var hit=$("#f_VirtualHit").attr("value");
												var siteId={SiteId};
												if((hit==""||hit=="0")){
													//var addHit=GetRandomNum(500,700);
													//$("#f_VirtualHit").attr("value",addHit);
												}
											});
											function GetRandomNum(Min,Max)
											{
												var Range = Max - Min;
												var Rand = Math.random();
												return(Min + Math.round(Rand * Range));
											}
										</script>
									</td>
								</tr>
								<!-- 加点击 end -->
							</table>
						</div>

						<div id="tabs-5">
							<div class="div_list" id="">
								<div>以往上传：</div>
								<ul id="old_pic_list">
									<icms id="document_news_pic" type="list">
										<item>
											<![CDATA[
											<li class="li_pic_img_item" id="UploadFileId_{f_UploadFileId}" style="">
												<div class="notice" id="notice_{f_UploadFileId}"></div>
												<table class="pic_img_container" cellspacing="0"><tr><td align="center" valign="center"><img class="pic_slider_img" onclick="showOriImg('{f_UploadFilePath}')" idvalue="{f_UploadFileId}" src="{f_UploadFilePath}" style="cursor:pointer;" title="点击查看原始图片"/></td></tr></table>


												<div class="pic_img_title" style="padding:3px 5px;">
													<input class="input_box" idvalue="{f_UploadFileId}" title="{f_UploadFileTitle}" id="pic_title_{f_UploadFileId}" value="{f_UploadFileTitle}" onclick="SetEventForDocumentNewsPicTitle({f_UploadFileId})">
												</div>
												<div class="pic_img_state" style="padding:3px 5px;">
													<div class="keep_pic" idvalue="{f_DocumentNewsPicId}" title="{f_ShowInPicSlider}" id="FormValues_{f_UploadFileId}"></div>
													<script type="text/javascript">ModifyShowInPicSlider({f_UploadFileId}, {f_ShowInPicSlider}, "keep_pic")</script>
													<img class="btn_modify btn_show_pic" idvalue="{f_DocumentNewsPicId}" src="/system_template/{template_name}/images/manage/start.jpg"
													     title="在控件中显示" alt="{f_UploadFileId}" onclick="ModifyShowInPicSlider('{f_UploadFileId}', '1', 'modify_pic')"/>
													<img class="btn_modify btn_hide_pic" idvalue="{f_DocumentNewsPicId}" src="/system_template/{template_name}/images/manage/stop.jpg"
													     title="在控件中隐藏" alt="{f_UploadFileId}" onclick="ModifyShowInPicSlider('{f_UploadFileId}', '0', 'modify_pic')"/>
													<a href="/default.php?secu=manage&mod=upload_file&m=modify&table_id={ChannelId}&site_id={SiteId}&upload_file_id={f_UploadFileId}">
														<img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif"
														     title="编辑" />
													</a>
													<img class="btn_modify" src="/system_template/{template_name}/images/manage/delete.jpg"
													     title="删除" onclick="DeleteDocumentNewsPic('{f_UploadFileId}')"/>
													<div class="btn_update_title" style="float:right;cursor: pointer;display: none" idvalue="{f_UploadFileId}" title="{f_ShowInPicSlider}" id="update_pic_title_{f_UploadFileId}" onclick="AjaxUpdateDocumentNewsPicTitle({f_UploadFileId})" >点击修改</div>
												</div>

											</li>

											]]>
										</item>
									</icms>
									<li style="clear:left;"></li>
								</ul>
							</div>

							<div class="div_list" id="">
								<div style="border-top: 1px dashed #D5D5D5">新上传：</div>
								<ul id="new_pic_list">
								</ul>
							</div>
							<input id="delete_pic_list" name="delete_pic_list" value="" type="hidden"/>
							<input id="modify_pic_list" name="modify_pic_list" value="" type="hidden"/>
							<input id="create_pic_list" name="create_pic_list" value="" type="hidden"/>
						</div>
					</div>
				</td>
			</tr>
		</table>

		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="60" align="center">
					<input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
					<input class="btn" value="确认并编辑" type="button" onclick="submitForm(2)"/>
					<input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
					<input class="btn" value="取 消" type="button" onclick="closeTab()"/>
				</td>
			</tr>
		</table>

	</form>
</div>
<div id="dialog_abstract_box" title="编写摘要" style="display:none;">
	<div id="dialog_abstract_content">

	</div>
</div>

</body>
</html>
