var gameId;
var pageNumber = 1;
var baseUrl = $('base').attr('href');
$(document).ready(function() {
    $('#loading').show();
    setTimeout(function() {
        loadGame(gameId, pageNumber);
    }, 1000);
    $('.header-toggle').click(function() {
        var id = $(this).data('id');
        if ($(this).hasClass('toggle-in')) {
            $(this).removeClass('toggle-in');
        } else {
            $(this).addClass('toggle-in');
        }
        $(id).toggle('slide');
    });
    $('button.game-list__button').click(function() {
        if ($(this).attr('data-id')) {
            gameId = $(this).data('id');
            $('button.game-list__button').each(function(key, val) {
                if ($(val).attr('data-id')) {
                    if ($(val).hasClass('fill')) {
                        $(val).removeClass('fill');
                    }
                }
            });
            $(this).addClass('fill');
            $('#list-account').empty();
            $('#loading').show();
            setTimeout(function() {
                pageNumber = 1;
                loadGame(gameId, pageNumber);
            }, 1000);
        }
    });
    $(document).on('click', '.dialog__close, .dialog__button-close', function() {
        $('#over').remove();
        $('.dialog').remove();
    });
    $(document).on('click', '#btn-rent', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var time = $(this).parent().children('#time-rent').val();
        $('<div/>').attr('id', 'over').appendTo('body');
        $('<div/>').addClass('dialog').appendTo('body');
        $('<div/>').attr('id', 'loading').appendTo('.dialog');
        rentAccount(id, time);
    });
    var currentCardType;
    $(document).on('click', '#card-type', function(e) {
        e.preventDefault();
        $(currentCardType).removeClass('selected');
        $(this).addClass('selected');
        currentCardType = this;
    });
    $(document).on('click', '#create-shop', function() {
        $('#over').remove();
        $('.dialog').remove();
        $.get({
            url: baseUrl + '/user/create-shop',
            success: function(result) {
                $('<div/>').attr('id', 'over').appendTo('body');
                $('body').append(result);
            }
        })
    });
    $(document).on('click', '#btn-create-shop', function(e) {
        e.preventDefault();
        var phone = $('#shop-phone').val();
        createShop(phone);
    });
    $(document).on('click', '#btn-game-avatar', function(e) {
        e.preventDefault();
        var type = $('input[name=game_avatar]').attr('type');
        if (type == 'file') {
            $('input[name=game_avatar]').attr('type', ' text').val('http://');
            $(this).text('Ảnh');
        } else {
            $('input[name=game_avatar]').attr('type', 'file');
            $(this).text('URL');
        }
    });
    $(document).on('change', 'input[name=game_avatar]', function() {
        var type = $('input[name=game_avatar]').attr('type');
        if (type == 'file') {
            var reader = new FileReader();
            reader.onload = function() {
                var dataURL = reader.result;
                var img = $('<img/>').attr('src', dataURL);
                $('#game-avatar').html(img);
            };
            reader.readAsDataURL($('input[name=game_avatar]')[0].files[0]);
        } else {
            var img = $('<img/>').attr('src', $('input[name=game_avatar]').val());
            $('#game-avatar').html(img);
        }
    });
    $(document).on('click', '#add-price-element', function(e) {
        e.preventDefault();
        $('#price-addon').append('<div class="group"><input type="" name="hour[]" placeholder="Thời gian: 1, 2 ,3 (giờ)"><input type="" name="price[]" placeholder="Giá: 2000, 3000, 4000..."></div>');
    });
    $(document).on('click', '#btn-change-price', function(e) {
        e.preventDefault();
        var price = $(this).parent().parent().children('#user-price');
        price.html('<input value="' + price.text() + '">');
        $(this).text('XÁC NHẬN');
        $(this).attr('id', 'btn-submit-pay');
    });
    $(document).on('click', '#btn-submit-pay', function(e) {
        e.preventDefault();
        var price = $(this).parent().parent().children('#user-price').children().val();
        var id = $(this).data('id');
        $.ajax({
            url: location.href + '/paid',
            type: "GET",
            data: { 'id': id, 'price': price },
            success: function(result) {
                location.reload();
            },
            error: function(err) {
                console.log(err)
            }
        });
    });
    $(document).on('click', '#btn-recharge', function(e) {
        e.preventDefault();
        var cardType = $('.card-type .selected').data('type');
        var data = new FormData($('#card-detail')[0]);
        data.append('type', cardType);
        var pin = $('input[name=pin]').val();
        var seri = $('input[name=seri]').val();
        if (cardType == 'undefined' || cardType == null || cardType == '') {
            $('#error-message').addClass('alert alert-danger').text('Vui lòng chọn loại thẻ!');
            return false;
        } else if (pin == null || pin == '' || pin == 'undefined') {
            $('#error-message').addClass('alert alert-danger').text('Vui lòng nhập mã PIN!');
            $('input[name=pin]').focus();
            return false;
        } else if (seri == null || seri == '' || seri == 'undefined') {
            $('#error-message').addClass('alert alert-danger').text('Vui lòng nhập số Seri!');
            $('input[name=seri]').focus();
            return false;
        } else {
            $('.card').empty();
            $('<div/>').attr('id', 'loading').appendTo('.card');
            rechargeMoney(data);
        }
    });
    $('#form-add-account').submit(function(e) {
        var _this = this;
        e.preventDefault();
        $(_this).children('button').text('ĐANG THÊM...');
        var data = new FormData(this);
        $.ajax({
            url: location.href,
            type: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                location.href = result;
            },
            error: function(err) {
                $(_this).children('button').text('THÊM TÀI KHOẢN');
                $('#error-message').html(err.responseJSON.error);
            }
        });
    });
    $('#form-edit-account').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
        $.ajax({
            url: location.href,
            type: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                location.href = result;
            },
            error: function(err) {
                $('#error-message').html(err.responseJSON.error);
            }
        });
    });
    $('#form-add-category').submit(function(e) {
        e.preventDefault();
        var _this = this;
        $(_this).children('button').text('ĐANG THÊM...');
        var data = new FormData(this);
        $.ajax({
            url: location.href,
            type: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                location.href = result;
            },
            error: function(err) {
                $('#error-message').html(err.responseJSON.error);
                $(_this).children('button').text('THÊM LOẠI GAME');
            }
        });
    });
});

$(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() > $(document).height() - 150 && $(window).scrollTop() + $(window).height() < $(document).height() - 140) {
        if ($('#list-account').children().length < $('#account-ready').text()) {
            pageNumber++;
            $('#loading').show();
            loadGame(gameId, pageNumber);
        }
    }
});
var isLoading = false;
function loadGame(id, page) {
    if(isLoading){
        return false;
    }
    isLoading = true;
    $.ajax({
        url: baseUrl + '/game/account-list',
        type: 'POST',
        data: { 'id': id, 'page': page },
        success: function(result) {
            $('#list-account').append(result.html);
            $('#account-ready').text(result.account_ready);
            $('#account-renting').text(result.account_renting);
            $('#loading').hide();
            isLoading = false;
        }
    });
}

function rentAccount(accountId, timeRent) {
    $.ajax({
        url: baseUrl + '/user/rent-account',
        type: 'POST',
        data: { 'id': accountId, 'time': timeRent },
        success: function(result) {
            $('.dialog').remove();
            $('body').append(result);
        },
        error: function(err) {
            $('.dialog').remove();
            $('body').append(err.responseJSON.error);
        }
    });
}

function popupRecharge() {
    $('#over').remove();
    $('.dialog').remove();
    $.get({
        url: baseUrl + '/user/recharge',
        success: function(result) {
            $('<div/>').attr('id', 'over').appendTo('body');
            $('body').append(result);
        }
    })
}

function rechargeMoney(data) {
    $.ajax({
        url: baseUrl + '/user/recharge',
        type: "POST",
        contentType: false,
        cache: false,
        processData: false,
        data: data,
        success: function(result) {
            $('.card').empty();
            $('.card').append(result);
        },
        error: function(err) {
            $('.card').empty();
            $('.card').append(err.responseJSON.error);
        }
    })
}
String.prototype.toTime = function() {
    var sec_num = parseInt(this, 10);
    var hours = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours < 10) { hours = "0" + hours; }
    if (minutes < 10) { minutes = "0" + minutes; }
    if (seconds < 10) { seconds = "0" + seconds; }
    return hours + ':' + minutes + ':' + seconds;
}
setInterval(function() {
    $('td#time_count').each(function(key, val) {
        var time = Math.ceil($(val).data('time') - Math.floor(Date.now() / 1000));
        if (time <= 0) {
            $(val).attr('id', '').html('<b style="color:red">ĐỔI PASS</b>');
            $.get({
                url: baseUrl + '/update-account'
            });
            return true;
        }
        $(val).html(time.toString().toTime());
    });
}, 1000);

function copyPassword(value) {
    var temp = $('<input>');
    $('body').append(temp);
    $(temp).val(value).select();
    document.execCommand("Copy");
    temp.remove();
    alert("Đã copied!");
}

function refundMoney(id) {
    if (confirm("Hoàn tiền cho tài khoản này?")) {
        $.get({
            url: baseUrl + '/admin/refund',
            data: { 'id': id },
            success: function() {
                location.reload();
            }
        });
    }
}

function removeAccount(id) {
    if (confirm("Xoá tài khoản sẽ không thể khôi phục lại. Tiếp tục?")) {
        $.get({
            url: baseUrl + '/user/shop/remove-account/' + id,
            success: function() {
                location.reload();
            }
        });
    }
}

function refundUserMoney(id) {
    if (confirm("Hoàn tiền cho tài khoản này?")) {
        $.get({
            url: baseUrl + '/user/shop/refund',
            data: { 'id': id },
            success: function() {
                location.reload();
            }
        });
    }
}

function createShop(phone) {
    $.ajax({
        url: baseUrl + '/user/create-shop',
        type: "POST",
        data: { 'phone': phone },
        success: function(result) {
            $('.form-group').empty().append('<div id="loading"></div>');
            setTimeout(function() {
                $('.form-group').empty().append(result);
            }, 2000);
        },
        error: function(err) {
            $('#error-message').html(err.responseJSON.error);
        }
    })
}

function submitForm(form) {
    $(form).parent().submit();
}