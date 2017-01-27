/**
 * Created by sazanof on 11.01.2017.
 */
$(document).ready(function () {
    var previewBtn = $('#preview');
    var deleteBtn = $('#delete');
    var modal = $('#letter');
    var modal_body = $('.modal-body');

    var send = $('#sendLetter');
    var sendLetter = $('.sendLetter');

    previewBtn.click(function() {
        modal.modal();
        $.ajax({
            type:"POST",
            success :function (txt) {
                modal_body.html(txt)
            }
        })
    });
    // функция для циклической отправки писем, если php max_execution time превышен
    function sendLettrersIfError(v){
        $.ajax({
            type:"POST",
            url:document.location.href+v,
            beforeSend: function (jqXHR,b) {
                //var response = $.parseJSON(txt);
                //console.log(jqXHR.responseText);
                $(".sendLetterBody").html('Отправка письма выбранным подписчикам. Пожалуйста, подождите.');
            },
            success :function (txt) {
                // формируем ответ в виде json
                var response = $.parseJSON(txt);
                if (response.num!=0){
                    sendLettrersIfError(v);
                }
                $(".sendLetterBody").html(response.answer + ' ('+ response.num +')');
            },
            error:function(){
                sendLettrersIfError(v);
            }
        });
    }
    sendLetter.click(function () {
        if (confirm('Начать рассылку?')){
            var v = $(this).attr('href');
            console.log(document.location.href+v);
            send.modal();
            sendLettrersIfError(v);
        }
        return false;
    })
});