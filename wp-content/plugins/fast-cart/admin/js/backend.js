;(function ($) {

    // $(document).ready(function(){

    // Color Picker
    $('.color-field').wpColorPicker();

    // Initilization of Select 2 JS
    $('.wpx-multiselect').select2();

    // Toggle for all icons
    $('.more-icons').on('click', function(e){

        var link = $(this);

        e.preventDefault();
        $('.all-icons').slideToggle('slow', function(){
              if ($(this).is(':visible')) {
                link.addClass('close');
              }
              else {
                link.removeClass('close');                
              }   
        });

    });

    // Replace display icon, based on selection from more icons 
    $( "#base-list li input[type='radio']" ).on( "click", function(){
        var icon = $(this).closest("li").find("i").attr( "class" );
        var prevIcon = $(".radiolist li i").attr( "class" );
        $(".radiolist li i").removeClass( prevIcon ).addClass( icon );
    });


    // Initially show the following settings <tr>
    $('tr.child.'+fcw_settings.mode).show();

    // Hide overlay
    $('tr.child-overlay').show();
    if( fcw_settings.overlay_layer === 'yes' ){
        $('tr.child-overlay').hide();
    }

    // Floating icon
    $('tr.child-floating_icon').show();
    if( fcw_settings.float_icon === 'no' ){
        $('tr.child-floating_icon').hide();
    }

    // Quantity field
    $('tr.child-qty_field').show();
    if( fcw_settings.qty_field === 'no' ){
        $('tr.child-qty_field').hide();
    }

    // Empty Cart
    $('tr.child-empty_cart').show();
    if( fcw_settings.empty_cart === 'no' ){
        $('tr.child-empty_cart').hide();
    }
    


    // Dependent table row for options - <select>
    $( '.wpx-table select' ).on( 'change', function(){
        let value = $(this).val();
        $('tr.child').hide();
        $('tr.child.'+value).show();
        changeAlternateClass();
    });

    // Dependent table row for options - <toggle>
    // $('tr.child-'+fcw_settings.mode).show();
    $( '.wpx-table input[type="checkbox"]' ).on( 'click', function(){
        // Checking if toggle is enabled or not.
        let checked = $(this).is(":checked");
        // Targeting the 'tr' class to replace the 'parent-' text
        let target_class = $(this).closest('tr').attr('class').replace('parent-', ''),
            // If no classes are found, get only 'target_class' value without 'parent-' text
            target = target_class ;
        // Searching for 'alternate' text within the target value
        if(target && target.indexOf('alternate') != -1){
            // If found remove the 'alternate' class
            target = target_class.replace('alternate', '').trim();
        }
        // Searching for 'new' class within within the target value
        if(target && target.indexOf('new') != -1){
            // If found remove the 'new' text
            target = target.replace('new', '').trim();
        }
        // Checking if the toggle checked and target exists
        if( checked && target ){
            $('.child-'+target).show();
            // Exception for hide_overlay option
            if( target === 'overlay' ){
                $('.child-'+target).hide();
            }
        }
        // If toggle not checked
        else{
            $('.child-'+target).hide();
            // Exception for hide_overlay option
            if( target === 'overlay' ){
                $('.child-'+target).show();
            }
        }

        changeAlternateClass();
        
    });

    function changeAlternateClass(){
        $( '.wpx-table' ).each( function( key, value ){
            $(this).find('tr:visible:odd').removeClass('alternate');
            $(this).find('tr:visible:even').addClass('alternate');
        });
        
    }

    // Kick start alternate class function
    changeAlternateClass();


})(jQuery);