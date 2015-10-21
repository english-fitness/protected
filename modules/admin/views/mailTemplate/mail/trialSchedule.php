<div style="font-size:12.8px">
	<div dir="ltr">
		<div>
			<p>
				<span>Chào mừng bạn <span class="mail-attr" data-attribute="name"><?php echo isset($name) ? $name : ""?></span> đã đến với khóa học tiếng Anh online của Speak up.</span>
			</p>
		</div>
		<div>
			<div>
				<p>Cảm ơn bạn đã chọn Speak up là người đồng hành trên con đường chinh phục tiếng Anh của mình.</p>
			</div>
			<div>
				<p>
					Chúng tôi xin thông báo các thông tin về buổi học thử với giáo viên nước ngoài của bạn như sau:<br>
				</p>
				<table style="border-top:dashed 1px black;border-bottom:dashed 1px black; border-spacing:0 10px; border-collapse:separate; margin-bottom:10px">
					<tr>
						<td>Ngày học</td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<span class="mail-attr" data-attribute="date"><?php echo isset($date) ? $date : ""?></span></td>
					</tr>
					<tr>
						<td>Thời gian</td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<span class="mail-attr" data-attribute="time"><?php echo isset($time) ? $time : ""?></span></td>
					</tr>
					<tr>
						<td>User (Hệ thống và Skype)</td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<span class="mail-attr" data-attribute="username"><?php echo isset($username) ? $username : ""?></span></td>
					</tr>
					<tr>
						<td>Password (Hệ thống và Skype)</td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<span class="mail-attr" data-attribute="password"><?php echo isset($password) ? $password : "speakup.vn"?></span></td>
					</tr>
				</table>
			</div>
			<?php $this->renderPartial("/mailTemplate/mail/instructions");?>
		</div>
		<div>
			<br>
			<p>Chúc bạn có một khóa học vui và hiệu quả.</p>
			<p>Xin cảm ơn!</p>
		</div>
	</div>
</div>