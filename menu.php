<?php 

$USERAUTH = new UserAuth();

$SESSION = new Session();

$SessionUser = $SESSION->get_session_user();

$menuPath = $USERAUTH->FrontEnd_show_menu($SessionUser);

// pr($menuPath);
?>

<aside style="background:#003c1d ">

		<nav>
			<ul>
				<?php
				if(isset($_SESSION['ses_utoken'])){
				?>
                    <li  align="center">
                        <ul>

                            <li class="icohome">
                                <div style="float:left;width:50%;background-color:#003c1d ">
                                    <a href="<?php echo "$url_rewrite";?>" style="background-color:#003c1d">
                                        <i class="fa fa-home fa-fw fa-3x"></i>
                                    </a>
                                </div>
                                <div style="float:left;width:50%;background-color:#003c1d ">
                                    <a href="<?=$url_rewrite?>/persediaan">
                                        <h5><i class="fa fa-mail-forward"></i> SIMSEDIA</h5>
                                    </a>
                                </div>

                            </li>
                        </ul>
                    </li>
				<li  align="center">
					<ul>
							<li class="home">
								<table border="0" width="100%">
									<tr>
										<td>
											<a href="#"><i class="fa fa-user fa-fw fa-5x"></i></a> 
										</td>
										<td>
											<?php echo $_SESSION['ses_uname'];?><br/>
											Welcome Back

											 <a data-toggle="modal" href="#chgpass" class="btn btn-small btn-primary ahover">Ubah Password</a>

										</td>
									</tr>
								</table>
							</li>
					</ul>
				</li>
				<?php 
				}else{
					?>

				<li  align="center">
					<ul>

						<li class="icohome">
                            <div style="float:left;width:50%;background-color:#003c1d ">
                                <a href="<?php echo "$url_rewrite";?>" style="background-color:#003c1d">
                                    <i class="fa fa-home fa-fw fa-3x"></i>
                                </a>
                            </div>
                            <div style="float:left;width:50%;background-color:#003c1d ">
                                <a href="<?=$url_rewrite?>/persediaan">
                                    <h5><i class="fa fa-mail-forward"></i> SIMSEDIA</h5>
                                </a>
                            </div>

                        </li>
					</ul>
				</li>
					<?php

				}
					$i = 0;

					if(!empty($menuPath))
					{

					// pr($menuPath);
					foreach ($menuPath[0] as $key => $menuParent)
					{
						
				?>
				<li><h3><?php echo $key; ?></h3>
					<ul>
				<?php 
					$j = $i;
					foreach ($menuParent as $key => $menuChild)
					{
						//echo $v2;
				?>
						<a href="<?php echo $url_rewrite."/module/".$menuPath[1][$key]; ?>"><li><?php echo $menuChild?></li></a>
				<?php 
					$j++;
					}
				?>
				</ul>
				</li>
				<?php 
					$i = $j;
					
				}

				}
				?>
				
			</ul>
		</nav>
	</aside>