var formPageDataFromPhp;jQuery(function(){var $=jQuery;var $forms=[];var formPageData=JSON.stringify(formPageDataFromPhp);try{$forms=$('form[method="post" i]');}catch(err){}
if($forms.length==0)
{$forms=$('form[method="post"]');}
if($forms.length==0)
{$forms=$('form[method="POST"]');}
$forms.each(function(i,form){var $form=$(form);var fieldsTypes={};$('input, textarea, select',$form).each(function(i,el){var $field=$(el);var name=$field.attr('name');var type=$field.attr('type');var placeholder=$.trim($field.attr('placeholder'));var id=$field.attr('id');var label='';if(id){var $form=$field.closest('form');var $label=$('label[for='+id+']',$form);label=$.trim($label.text());}
if(!name)return;if(!type){type=$field.prop('tagName').toLowerCase();}
fieldsTypes[name]={type:type,placeholder:encodeURIComponent(placeholder),label:encodeURIComponent(label)};});fieldsTypes=JSON.stringify(fieldsTypes);var $pageDataField=$('<input type="hidden" name="form_page_data_for_uco_crm_integration" value="" />');$pageDataField.appendTo($form);$pageDataField.val(formPageData);var $typesField=$('<input type="hidden" name="form_fields_types_for_uco_crm_integration" value="" />');$typesField.appendTo($form);$typesField.val(fieldsTypes);});});