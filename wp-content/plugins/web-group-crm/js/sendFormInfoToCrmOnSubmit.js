// Object of params which should be passed by PHP wp_localize_script()
var formPageDataFromPhp;

jQuery(function(){
    var $ = jQuery;
	var $forms = [];
    var formPageData = JSON.stringify(formPageDataFromPhp);

    // select only POST-forms
	try {
    	$forms = $('form[method="post" i]');
    } catch (err) {}

    if (!$forms.length) {
       $forms = $('form[method="post"]');
    }
    if (!$forms.length) {
        $forms = $('form[method="POST"]');
    }

    $forms.each(function(i, form){
        var $form = $(form);
        var formType = getFormType($form);
        var fieldsTypes = {};
        
        $('input, textarea, select, file', $form).each(function(i, el){
            var $field = $(el);

            var name = $field.attr('name');
            var type = $field.attr('type');
            var placeholder = $.trim( $field.attr('placeholder') );
            var id = $field.attr('id');
            var label = '';
            var $form = $field.closest('form');

            if (id) {
                var $label = $('label[for=' + id + ']', $form);
                label = $.trim( $label.text() );
            }

            if (!name) return;

            if (!type) {
                type = $field.prop('tagName').toLowerCase();
            }
            
            if (type === 'checkbox') {
                name = name.replace(/\[\]$/, '');
                placeholder = '';
                label = getCheckboxLabel($field, formType);
                
            } else if (type === 'radio') {
                placeholder = '';
                label = getRadioButtonsLabel($field, formType);
            }

            fieldsTypes[name] = {
                type : type,
                placeholder : encodeURIComponent(placeholder),
                label : encodeURIComponent(label)
            };
        });

        fieldsTypes = JSON.stringify(fieldsTypes);

        var $pageDataField = $('<input type="hidden" name="form_page_data_for_uco_crm_integration" value="" />');
        $pageDataField.appendTo($form);
        $pageDataField.val(formPageData);
        
        var $pageDataField = $('<input type="hidden" name="form_type_for_uco_crm_integration" value="" />');
        $pageDataField.appendTo($form);
        $pageDataField.val(formType);

        var $typesField = $('<input type="hidden" name="form_fields_types_for_uco_crm_integration" value="" />');
        $typesField.appendTo($form);
        $typesField.val(fieldsTypes);

    });
    
    
    function getFormType($form) {
        var formType = '';
            
        if ($form.hasClass('elementor-form')) {
            formType = 'elementor';
            
        } else if ($form.hasClass('pojo-form')) {
            formType = 'pojo';
            
        } else if ($form.hasClass('wpcf7-form')) {
            formType = 'cf7';
            
        } else if ($form.hasClass('visual-form-builder')) {
            formType = 'vfb';
            
        } else if (1) {
            formType = 'divi';
        }
        
        return formType;
    }
    
    
    function getCheckboxLabel($field, formType)
    {
        var label = '';
        var $label = [];
        
        if (formType === 'elementor') {
            var $chFieldGroup = $field.closest('.elementor-field-group');
            $label = $chFieldGroup.children('label');
            
        } else if (formType === 'pojo') {
            var $chFieldGroup = $field.closest('.field-group');
            $label = $chFieldGroup.children('label');
            
        } else if (formType === 'cf7') {
            var $chFieldGroup = $field.closest('.checkboxesGroup');
            $label = $chFieldGroup.children('.checkboxesGroupLabel');
            
        } else if (formType === 'vfb') {
            var $chFieldGroup = $field.closest('.vfb-item-checkbox');
            $label = $chFieldGroup.children('.vfb-desc');
            
        }
        
        if ($label.length) {
            label = $label.text();
        }
        
        return label;
    }
    
    
    function getRadioButtonsLabel($field, formType)
    {
        var label = '';
        var $label = [];
        
        if (formType === 'elementor') {
            var $chFieldGroup = $field.closest('.elementor-field-group');
            $label = $chFieldGroup.children('label');
            
        } else if (formType === 'pojo') {
            var $chFieldGroup = $field.closest('.field-group');
            $label = $chFieldGroup.children('label');
            
        } else if (formType === 'cf7') {
            var $chFieldGroup = $field.closest('.radioGroup');
            $label = $chFieldGroup.children('.radioGroupLabel');
            
        } else if (formType === 'vfb') {
            var $chFieldGroup = $field.closest('.vfb-item-radio');
            $label = $chFieldGroup.children('.vfb-desc');
            
        }
        
        if ($label.length) {
            label = $label.text();
        }
        
        return label;
    }

});

