// array of functions that will be executed on init
$(
    function(){
        $("#c3alert").notify({expires: 15000});
    }
);

//
//NOTE: templates are defined in main.php in layouts page (at the bottom of the page)
//

function c3alert(data){
    opts = !data.opts ? {custom : true} : data.opts;
    template = !data.template ? "success" : data.template;
    vars = {
        text : data.text
    };
    
    return $('#c3alert').notify("create", template, vars, opts);
}