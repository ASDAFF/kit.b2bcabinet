/*
 * Copyright (c) 2017. Sergey Danilkin.
 */
$(document).on("click", "#blank_excel_in", function()
{
    $('#excel_in_form').fadeToggle();
});

$(document).on("click", "#blank_excel_out", function()
{
    localStorage.setItem('needBlankOut', 'Y');
    location.href = $(this).attr('data-path-to-blank');
});