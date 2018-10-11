<nav class="navbar navbar-inverse  navbar-static-top"> <!-- navbar-fixed-top" > -->
		<div class="navbar-inner">
				<div class="nav-collapse collapse d-flex flex-justify-between px-3 container-lg">
					<ul class="nav navbar-nav navbar-left">
					<li id="main_page" ><a href="/">HOME</a></li>
					<li id="shift" ><a href="/shift.php" >选班</a></li>
					<li id="arrange" >  <a href="/arrange.php">排班控制台</a></li>
					<li id="arrange_configure" > <a href="/arrange_configure.php">定制排班</a></li>
					<li id="manage" > <a href="/contacts.php">通讯录</a></li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<?php if(isset($_SESSION["wname"])){
									echo $_SESSION["wname"];
								}else
								   echo "个人中心";
								?> 
								<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li id="login_page" ><a href="login.php">登录</a></li>
									<li class="navbar-right"> <a href="/logout.php">注销</a></li>
									<li role="separator" class="divider"></li>
									<li id="selfinfo">  <a href="/selfinfo.php">个人信息</a></li>
								</ul>
							</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
