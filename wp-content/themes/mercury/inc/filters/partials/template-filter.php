<?php if ( ! empty( $result ) ) : ?>
	<form method="GET" action="">
		<div class="mercury-filter__wrapper">
			<ul class="mercury-filter-nav nav nav-tabs" id="myTab" role="tablist">
				<?php $filter_titles = array_keys( $result ); ?>
				<?php if ( ! empty( $filter_titles ) ) : ?>
					<?php foreach ( $filter_titles as $i => $title ) : ?>
						<li class="mercury-filter-nav__item nav-item" role="presentation">
							<span class="icon-plus"></span>
							<button class="nav-link" id="home-tab" data-bs-toggle="tab"
									data-bs-target="#<?php echo $title ?>"
									type="button"
									role="tab" aria-controls="<?php echo $title ?>"
									aria-selected="true"><?php echo $names[ $i ] ?>
							</button>
						</li>
					<?php endforeach; ?>
				<?php endif ?>
			</ul>
			<div class="clear-filter">
				<button id="clear-filter" type="button"><?php _e( 'Clear filter', 'mercury' ) ?></button>
			</div>
		</div>
		<div class="mercury-filter-content tab-content">
			<?php foreach ( $result as $key => $section ) : ?>
				<div class="mercury-filter-content__tab tab-pane" id="<?php echo $key ?>" role="tabpanel"
						aria-labelledby="<?php echo $key ?>-tab">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<div class="tab-items-wrapper">
									<?php if ( ! empty( $section ) ) : ?>
										<?php foreach ( $section as $k => $item ) : ?>
											<div class="tab-item">
												<?php if ( is_object( $item ) ) : ?>
													<div class="tab-item__label tab-item__label-input">
														<label for="filter_<?php echo $item->term_id ?>">
															<input
																	id="filter_<?php echo $item->term_id ?>"
																	name="<?php echo 'filter[' . $key . '][]' ?>"
																	value="<?php echo $item->term_id ?>"
																<?php
																if ( isset( $_GET['filter'][ $key ] ) ) {
																	checked( in_array( $item->term_id, $_GET['filter'][ $key ] ) );
																}
																?>
																	type="checkbox"><?php echo $item->name ?></label>
													</div>
												<?php elseif ( is_array( $item ) ) : ?>
													<div class="tab-item__label"><?php echo get_taxonomy( $k )->label ?></div>
													<ul>
														<?php foreach ( $item as $s_k => $s_item ) : ?>
															<li><label for="filter_<?php echo $s_item->term_id ?>">
																	<input
																			id="filter_<?php echo $s_item->term_id ?>"
																			name="<?php echo 'filter[' . $k . '][]' ?>"
																			value="<?php echo $s_item->term_id ?>"
																		<?php
																		if ( isset( $_GET['filter'][ $k ] ) ) {
																			checked( in_array( $s_item->term_id, $_GET['filter'][ $k ] ) );
																		}
																		?>
																			type="checkbox"></label><?php echo $s_item->name ?>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>
					<div class="mercury-filter__submit">
						<button type="submit">SUBMIT</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</form>
<?php endif; ?>
