<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

global $wpdb;

$page_url = WLSM_M_Staff_Class::get_notices_page_url();

$school_id = $current_school['id'];

$notice = NULL;

$nonce_action = 'add-notice';

$title       = '';
$description = '';
$url         = '';
$attachment  = '';
$link_to     = 'url';
$is_active   = 1;
$class_id    = NULL;
$section_id    = NULL;

$notice_classes = array();
$notice_section = array();
$notice_students = array();

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id     = absint($_GET['id']);
	$notice = WLSM_M_Staff_Class::fetch_notice($school_id, $id);

	if ($notice) {
		$nonce_action = 'edit-notice-' . $notice->ID;

		$title           = $notice->title;
		$description     = $notice->description;
		$url             = $notice->url;
		$attachment      = $notice->attachment;
		$link_to         = $notice->link_to;
		$is_active       = $notice->is_active;
		if (!is_null($notice->notice_data)) {
			$notice_data = unserialize($notice->notice_data);
		} else {
			$notice_data = null; // or an appropriate default value
		}
		if ($notice->notice_data) {
			$notice_classes  = $notice_data['classes'];
			$notice_section  = $notice_data['sections'];
			$notice_students = $notice_data['students'];
			$class_id = $notice_data['classes'][0];
			$section_id = $notice_data['sections'][0];
			$student_id = $notice_data['students'][0];
		}



	}
}
$classes = WLSM_M_Staff_Class::fetch_classes($school_id);
$sections = WLSM_M_Staff_Class::fetch_sections_by_class_id($class_id);
$students = WLSM_M_Staff_Class::fetch_students_by_section_id($section_id);
// var_dump($section_id); die;
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ($notice) {
						esc_html_e('Edit Notice', 'school-management');
					} else {
						esc_html_e('Add New Notice', 'school-management');
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url($page_url); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-newspaper"></i>&nbsp;
					<?php esc_html_e('View All', 'school-management'); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-notice-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-save-notice">

			<?php if ($notice) { ?>
				<input type="hidden" name="notice_id" value="<?php echo esc_attr($notice->ID); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_title" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Notice Title', 'school-management'); ?>:
						</label>
						<input name="title" class="form-control" id="wlsm_title" placeholder="<?php esc_attr_e('Enter notice', 'school-management'); ?>" cols="30" rows="3" value="<?php echo esc_html(stripslashes($title)); ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Class', 'school-management'); ?>:
						</label>
						<select name="classes[]" class="form-control selectpicker" data-nonce="<?php echo esc_attr(wp_create_nonce('get-class-sections')); ?>" data-nonce-subjects="<?php echo esc_attr(wp_create_nonce('get-class-subjects')); ?>" id="wlsm_class" data-live-search="true" multiple>
							<option value="all" <?php echo (in_array('all', $notice_classes) ? 'selected' : ''); ?>><?php esc_html_e('Select All', 'school-management'); ?></option>
							<?php foreach ($classes as $class) { ?>
								<option value="<?php echo esc_attr($class->ID); ?>" <?php echo (in_array($class->ID, $notice_classes) ? 'selected' : ''); ?>>
									<?php echo esc_html(WLSM_M_Class::get_label_text($class->label)); ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Section', 'school-management'); ?>:
						</label>
						<select name="sections[]" class="form-control selectpicker wlsm_section" id="wlsm_section" data-nonce="<?php echo esc_attr(wp_create_nonce('get-section-students')); ?>" data-live-search="true" title="<?php esc_attr_e('Select Section', 'school-management'); ?>" multiple>
							<option value="all"><?php esc_html_e('Select All', 'school-management'); ?></option>
							<?php foreach ($sections as $section) { ?>
								<option value="<?php echo esc_attr($section->ID); ?>" <?php selected(in_array($section->ID, $notice_section), true, true); ?>>
									<?php echo esc_html(WLSM_M_Staff_Class::get_section_label_text($section->label)); ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_student" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Student', 'school-management'); ?>:
						</label>
						<select name="students[]" class="form-control selectpicker" id="wlsm_student" data-live-search="true" title="<?php esc_attr_e('Select Student', 'school-management'); ?>" multiple>
							<option value="all"><?php esc_html_e('Select All', 'school-management'); ?></option>
							<?php foreach ($students as $student) { ?>
								<option value="<?php echo esc_attr($student->ID); ?>" <?php selected(in_array($student->ID, $notice_students), true, true); ?>>
									<?php echo esc_html(($student->label)); ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-12">
						<label for="wlsm_description" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Notice Description', 'school-management'); ?>:
						</label>
						<textarea name="description" class="form-control" id="wlsm_description" placeholder="<?php esc_attr_e('Enter notice', 'school-management'); ?>" cols="30" rows="3"><?php echo esc_html(stripslashes($description)); ?></textarea>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_link_to" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Link to', 'school-management'); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked('', $link_to, true); ?> class="form-check-input" type="radio" name="link_to" id="wlsm_link_to_none" value="">
							<label class="ml-1 form-check-label text-dark font-weight-bold" for="wlsm_link_to_none">
								<?php echo esc_html(WLSM_M_Staff_Class::get_none_text()); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked('attachment', $link_to, true); ?> class="form-check-input" type="radio" name="link_to" id="wlsm_link_to_attachment" value="attachment">
							<label class="ml-1 form-check-label text-dark font-weight-bold" for="wlsm_link_to_attachment">
								<?php echo esc_html(WLSM_M_Staff_Class::get_attachment_text()); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked('url', $link_to, true); ?> class="form-check-input" type="radio" name="link_to" id="wlsm_link_to_url" value="url">
							<label class="ml-1 form-check-label text-dark font-weight-bold" for="wlsm_link_to_url">
								<?php echo esc_html(WLSM_M_Staff_Class::get_url_text()); ?>
							</label>
						</div>
					</div>
					<div class="form-group col-md-8 wlsm-notice-link wlsm-notice-url">
						<label for="wlsm_url" class="wlsm-font-bold">
							<?php esc_html_e('Notice URL', 'school-management'); ?>:
						</label>
						<input type="text" name="url" class="form-control" id="wlsm_url" placeholder="<?php esc_attr_e('Enter notice URL', 'school-management'); ?>" value="<?php echo esc_url($url); ?>">
					</div>
					<div class="form-group col-md-8 wlsm-notice-link wlsm-notice-attachment">
						<div class="wlsm-attachment-box">
							<div class="wlsm-attachment-section">
								<label for="wlsm_attachment" class="wlsm-font-bold">
									<?php esc_html_e('Attachment', 'school-management'); ?>:
								</label>
								<?php if (!empty($attachment)) { ?>
									<a target="_blank" href="<?php echo esc_url(wp_get_attachment_url($attachment)); ?>"><i class="fas fa-search"></i></a>
								<?php } ?>
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input" id="wlsm_attachment" name="attachment">
									<label class="custom-file-label" for="wlsm_attachment">
										<?php esc_html_e('Choose Attachment File', 'school-management'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_status" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Status', 'school-management'); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked(1, $is_active, true); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_active" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_status_active">
								<?php echo esc_html(WLSM_M_Staff_Class::get_active_text()); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked(0, $is_active, true); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
								<?php echo esc_html(WLSM_M_Staff_Class::get_inactive_text()); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-notice-btn">
						<?php
						if ($notice) {
						?>
							<i class="fas fa-save"></i>&nbsp;
						<?php
							esc_html_e('Update Notice', 'school-management');
						} else {
						?>
							<i class="fas fa-plus-square"></i>&nbsp;
						<?php
							esc_html_e('Add New Notice', 'school-management');
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
