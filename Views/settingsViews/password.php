					
					



						<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">

											<h3><?php echo $loc->label("Password");?> </h3>

											<p style="margin-bottom: 5px;"><?php echo $loc->label("Change your password");?><p>

										</div>


										<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>
										
										
										<div class="panel-body" id="panel-body">
											<input type="hidden" name="tab" value="password" />
									
	<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										
												<div class="form-group">

													<h4><?php echo $loc->label("Current Password");?></h4>

												</div>

												<div class="form-group">

													<input type="password" name="password" id="password" class="form-control">
													
													<p><a href="forgot" style="color: #4FB5FD;"><?php echo $loc->label("Forgot Password?");?></a><p>


												</div>
												
											</div>
											</div>
											
												<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

												<div class="form-group">

													<h4><?php echo $loc->label("New Password");?></h4>

												</div>

												<div class="form-group">

													<input type="password" name="newPassword" id="newPassword" class="form-control">


												</div>
												
												</div>
												</div>
												
													<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

												<div class="form-group">

													<h4><?php echo $loc->label("Verify Password");?></h4>

												</div>

												<div class="form-group">
																	
												<input type="password" name="retype" id="retype" class="form-control">

												</div>
												
												</div>
												
												</div>

										</div>
										
										<div class="panel-footer" id="panel-footer" style="padding-top: 15px;">
										
											<center><div class="form-group">
												
												<button id="submit" type="submit" class="btn btn-primary btn-rounded"><?php echo $loc->label("Save");?></button>
												
											</div></center>
											
										</div>
						</div>

