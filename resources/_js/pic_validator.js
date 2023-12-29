$(document).ready(function() {

    $('.js-service-pic').bind('change', function() {

        var size = this.files[0].size; // размер в байтах
        var name = this.files[0].name;

        if((10 * 1024 * 1024) < size) {
            alert('Недопустимый размер файла "'+name+'" (более 10МБ)!');
            $(this).val('');
            console.log('Недопустимый размер файла');
        }

        var fileExtension = ['jpg', 'jpeg']; // допустимые типы файлов

        if ($.inArray(name.split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert('Недопустимый тип файла "'+name+'"!');
            $(this).val('');
            console.log('Недопустимый тип файла');
        }

    });

});
