<div class="dialog">
    <div class="dialog__title">NẠP TIỀN VÀO TÀI KHOẢN</div>
    <div class="dialog__close">&#10060;</div>
    <div class="dialog__text">
        <b style="color:red">Chú ý:</b> Nếu gặp lỗi trong quá trình nạp thẻ, vui lòng liên hệ Admin trong thời gian sớm nhất để giải quyết. Xin cảm ơn!
		<div id="error-message"></div>
	</div>
	<div class="card">
		<div class="card-type">
			<div class="card-type__viettel" id="card-type" data-type="1"></div>
			<div class="card-type__mobifone" id="card-type" data-type="2"></div>
			<div class="card-type__vinaphone" id="card-type" data-type="3"></div>
		</div>
		<div class="card-detail">
			<form id="card-detail">
			{{ csrf_field() }}
			<label for="">Mã PIN:</label>
			<input type="text" name="pin" required>
			<label for="">Seri:</label>
			<input type="text" name="seri" required>
			<button id="btn-recharge">NẠP TIỀN</button>
			</form>
		</div>
	</div>
</div>