<?php include('db_connect.php'); ?>
<style>
	.col-md-4 {
		padding-top: 3%;
		margin-left: -100px;
	}

	.col-md-8 {
		padding-top: 3%;
	}

	.card {
		background-color: #1F1D2B;
		box-shadow: 0 5px 10px rgba(203, 219, 175, 0.1);
	}

	label {
		color: white;
	}

	.form-control {
		background: #EEEEEE;
	}

	.card-header {
		background-color: #FBDE44;
		color: black;
	}

	thead tr th {
		background-color: #7B7B7B;
		color: black;
	}

	td {
		vertical-align: middle !important;
		color: white;
	}

	td p {
		margin: unset;
		color: white;
	}

	tr {
		background-color: #252836 !important;
	}

	.custom-switch {
		cursor: pointer;
	}

	.custom-switch * {
		cursor: pointer;
	}
</style>
<!-- <?php
		// if(isset($))
		?> -->
<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-product">
					<div class="card">
						<div class="card-header">
							<b> Product Form</b>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category_id" id="category_id" class="custom-select select2">
									<option value=""></option>
									<?php
									$qry = $conn->query("SELECT * FROM categories order by name asc");
									while ($row = $qry->fetch_assoc()) :
										$cname[$row['id']] = ucwords($row['name']);
									?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea name="description" id="description" cols="30" rows="4" style="background: #EEEEEE;" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-right" name="price">
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="status" name="status" checked value="1">
									<label class="custom-control-label" for="status">Available</label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Image</label>
								<input type="file" class="form-control text-right" name="image" id="image" accept=".jpg, .jpeg, .png, .gif" value="">
							</div>
						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"><i class="bi bi-floppy-fill" style="color: white;"></i> Save</button>
									<button class="btn btn-sm btn-default btn-danger col-sm-3" type="button" onclick="$('#manage-product').get(0).reset()"><i class="bi bi-x-square-fill" style="color: white;"></i> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Product List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th class="text-center">S.no.</th>
									<th class="text-center">Category</th>
									<th class="text-center">Product Information</th>
									<th class="text-center" style="width: 130px" ;>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$product = $conn->query("SELECT * FROM products order by id asc");
								while ($row = $product->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<p><b><?php echo $cname[$row['category_id']] ?></b></p>
										</td>
										<td class="">
											<p>Name: <b><?php echo $row['name'] ?></b></p>
											<p><small>Price: <b><?php echo number_format($row['price'], 2) ?></b></small></p>
											<p><small>Status: <b><?php echo $row['status'] == 1 ? " Available" : "Unavailable" ?></b></small></p>
											<p><small>Description: <b><?php echo $row['description'] ?></b></small></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_product" type="button" data-id="<?php echo $row['id'] ?>" data-description="<?php echo $row['description'] ?>" data-name="<?php echo $row['name'] ?>" data-price="<?php echo $row['price'] ?>" data-status="<?php echo $row['status'] ?>" data-category_id="<?php echo $row['category_id'] ?>"><i class="bi bi-pencil-fill" style="color: white;"></i> Edit</button>
											<button class="btn btn-sm btn-danger delete_product" type="button" data-id="<?php echo $row['id'] ?>"><i class="bi bi-trash3-fill" style="color: white;"></i> Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>

<?php

?>
<script>
	$('#manage-product').on('reset', function() {
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	})
	$('#manage-product').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	})
	$('.edit_product').click(function() {
		start_load()
		var cat = $('#manage-product')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id')).trigger('change')
		if ($(this).attr('data-status') == 1)
			$('#status').prop('checked', true)
		else
			$('#status').prop('checked', false)
		end_load()
	})
	$('.delete_product').click(function() {
		_conf("Are you sure to delete this product?", "delete_product", [$(this).attr('data-id')])
	})

	function delete_product($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_product',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>