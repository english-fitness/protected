<style type="text/css">
	#careStatusGuide td{
		border: 1px solid black;
		padding:5px;
		cursor: default;
	}
	#careStatusGuide th{
		border: 1px solid black;
		padding:5px;
		text-align: center;
	}
</style>
<div class="row">
	<div class="col col-lg-12 mT10">
		<a href="#" class="fR" id="statusGuide">Hướng dẫn trạng thái</a>
	</div>
</div>
<div id="careStatusGuide" class="dpn">
	<table>
		<tr class="header-row">
			<th>Level</td>
			<th>Định nghĩa</td>
			<th>Dấu hiệu nhận biết</td>
		</tr>
		<tr>
			<td class="text-center">L0</td>
			<td>Là contact hợp lệ được tìm kiếm về chưa được xử lý dữ liệu</td>
			<td>Là DS contact thô gồm ít nhất 2 trường thông tin: tên, SĐT chưa được xử lý dữ liệu</td>
		</tr>
		<tr>
			<td class="text-center">L1</td>
			<td>Là contact đã qua xử lý dữ liệu (lọc trùng)</td>
			<td>Là DS contact không có các thông tin trùng nhau</td>
		</tr>
		<tr>
			<td class="text-center">L2</td>
			<td>Là Contact chưa liên lạc được</td>
			<td>- Gọi điện thoại không được, không nghe máy, số điện thoại sai</td>
		</tr>
		<tr>
			<td class="text-center">L3</td>
			<td>Contact liên lạc được</td>
			<td>
				- Liên lạc được<br>
				- Đúng tên, SĐT<br>
				- Có nhu cầu học<br>
				- Có phương tiện học theo học (máy tính nối mạng)<br>
				- Quan tâm tới chương trình học
			</td>
		</tr>
		<tr>
			<td class="text-center">L</td>
			<td>Hủy đưa ra ngoài Level</td>
			<td>
				- Không đúng người<br>
				- Không có nhu cầu học<br>
				- Đăng ký chơi
			</td>
		</tr>
		<tr>
			<td class="text-center">L4</td>
			<td>Là Contact quan tâm các khóa học của Speak up</td>
			<td>
				- Xin gửi email, xin tư vấn, hoặc đăng ký học thử...
				- Cung cấp thông tin đầy đủ, bố trí lịch học để sử dụng hệ thống
			</td>
		</tr>
		<tr>
			<td class="text-center">L5</td>
			<td>Là Contact đã được hướng dẫn sử dụng hệ thống</td>
			<td>
				- Đã được hướng dẫn sử dụng hệ thống
				- Đăng ký học thử với giáo viên nước ngoài
			</td>
		</tr>
		<tr>
			<td class="text-center">L6</td>
			<td>Là Contact hiểu đầy đủ thông tin các khóa học tại Speak up, đã học thử giáo viên, đã nhận bản đánh giá</td>
			<td>Đã học thử, được tư vấn gọi điện tư vấn đủ thông tin chương trình gồm: phương pháp học, học phí, cách thức đăng ký...</td>
		</tr>
		<tr>
			<td class="text-center">L8A</td>
			<td>Là Contact đăng ký 1 khóa học tại Speak up</td>
			<td>Đã thanh toán phí một khóa bất kỳ tại Speak up (đã đóng tiền, đã chọn khóa học)</td>
		</tr>
		<tr>
			<td class="text-center">L8B</td>
			<td>Là contact tại thời điểm nhận đã đăng ký nhiều hơn 1 khóa học hoặc 1 tài khoản tại Speak up</td>
			<td>Đã học và thanh toán nhiều hơn 1 khóa học hoặc 1 tài khoản của Speak up ( đã đóng tiền và chọn khóa học)</td>
		</tr>
		<tr>
			<td class="text-center">L9A</td>
			<td>Là Contact đăng ký tiếp các khóa tại Speak up</td>
			<td>Đã thanh toán thêm phí một khóa học khác tại Speak up (đã đóng tiền và đã chọn khóa học)</td>
		</tr>
		<tr>
			<td class="text-center">L9B</td>
			<td>Là Contact đăng ký học thêm nhiều hơn 1 khóa học hoặc 1 tài khoản tại Speak up</td>
			<td>Đã thanh toán thêm phí nhiều hơn một khóa học hoặc nhiều hơn 1 tài khoản tại Speak up (đã đóng tiền và đã chọn khóa học)</td>
		</tr>
		<tr>
			<td class="text-center">L7A</td>
			<td>Là Contact đã học nhưng tạm dừng</td>
			<td>
				Contact đã tham gia học nhưng tạm dừng vì:<br>
				 + Bận việc<br>
				 + Không bố trí được thời gian<br>
				 + Hết tiền
			</td>
		</tr>
		<tr>
			<td class="text-center">L7B</td>
			<td>Là Contact đã học nhưng tạm dừng</td>
			<td>
				Contact đã tham gia học nhưng tạm dừng vì:<br>
 				+ Không thấy chương trình dạy phù hợp
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<b>* Định nghĩa contact hợp lệ:</b><br>
				- Là contact đảm bảo ít nhất 2 trường thông tin: tên, điện thoại đúng quy cách (đủ 10,11 số với di động, 10 số với cố định).<br>
				- Nếu thiếu một trong 2 thông tin trên, contact là không hợp lệ, không nghiệm thu contact đó.
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	var dialog = new BootstrapDialog({
		title:'<span style="font-size:20px"><b>Hướng dẫn trạng thái chăm sóc</b></span>',
		message:function(){
			var careStatusGuide = $('#careStatusGuide').removeClass('dpn')[0];
			return careStatusGuide;
		},
		buttons:[{
			label:'Đóng',
			action:function(dialogRef){
				dialogRef.close();
			}
		}],
		autodestroy: false,
	});
	dialog.realize();
	dialog.getModalDialog().css('width', '90%');
	dialog.getModalHeader().css('text-align', 'center');
	dialog.getModalBody().css('padding', '30px 40px');
	var rows = dialog.getModalBody().find('tr:not(.header-row)');
	rows.each(function(){
		var $this = $(this);
		$this.hover(function(){
			rows.each(function(){
				$(this).css('background-color', '');
			});
			$this.css('background-color', 'lavender');
		});
	});

	$("#statusGuide").click(function(e){
		e.preventDefault();
		dialog.open();
	})
</script>