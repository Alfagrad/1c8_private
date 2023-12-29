// $(document).ready(function(){
//
//     $('.js-write-to-us, .js-to-director, .js-demping, .js-discount').click(function(e) {
//
//         // запускаем всплывающее окно
//         $('.js-mail-popup').fadeIn('slow' , 'linear');
//
//         // вписываем заголовок
//         $('.popup_title').html($(this).html());
//
//         // метка для типа сообщения
//         if($(this).hasClass('js-write-to-us')) {
//             $('.js-mail-popup input[name=feedback_type]').attr('value', {{ config('constants.emailTo.manager') }});
//         }
//         if($(this).hasClass('js-to-director')) {
//             $('.js-mail-popup input[name=feedback_type]').attr('value', {{ config('constants.emailTo.head') }});
//         }
//         if($(this).hasClass('js-demping')) {
//             $('.js-mail-popup input[name=feedback_type]').attr('value', {{ config('constants.emailTo.claim') }});
//         }
//     });
//
//     // закрываем всплывающее окно
//     $('.js-popup-close').click(function() {
//         $('.js-mail-popup').fadeOut('slow' , 'linear');
//     });
//
//     // меняем "Прикрепить файл" на название файла
//     $(".js-mail-popup input[type=file]").change(function(){
//
//         var filename = $(this).val().replace(/.*\\/, "");
//
//         // если слишком большой файл, выдаем предупреждение
//         if(this.files[0].size > 20*1024*1024 ){
//             $(this).val('');
//             alert('Ошибка! Размер файла должен быть менее 20 мб');
//
//         } else {
//             $('.popup_attach-file span').text(filename);
//         }
//
//     });
//
//
//     console.log('всплывашка письмо');
//
// });
