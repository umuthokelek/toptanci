$(window).load(function()
{
   var phones = [{ "mask": "+\\90 (###) ###-##-##"}, { "mask": "+90 (###) ###-##-##"}];
    $('#textbox').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
});