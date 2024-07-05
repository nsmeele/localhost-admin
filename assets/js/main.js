$(document).ready(function () {

    $('nav ul ul li.current-menu-item').parents('li.folder').addClass('child-active');


    $('a.edit-project').click(function (event) {
        event.preventDefault();
        $(this).next('table').slideToggle('slow');
    });

    $('')

    $('#select-place').on('change', function () {
        if ($(this).val() === "subproject") {
            $("#select-parent-project").show()
        } else {
            $("#select-parent-project").hide()
        }
    });


});

function toggleMenu(elem) {
    event.preventDefault();
    $(elem).next('.toggle-menu').toggle();
}