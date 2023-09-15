<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\Products;
use App\ProductImages;
use App\ProductCategories;
use App\ProductRelation;
use App\Brands;
use App\Colors;
use App\Materials;
use App\Sizes;
use App\Vendor;
use App\Category;
use App\ProductAttributes;
use Redirect;
use Exception;
use Validator;
use DB;
use File;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use URL;
use Auth;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
use ZipArchive;

class ProductController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function bulkActiveProduct(Request $request)
	{
		$prd_ids = explode(",", $request->product_ids);
		DB::table('products')
			->whereIn('id', $prd_ids)
			->update(
				[
					'status' => 1
				]
			);

		MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);

		return Redirect::back();
	}

	public function edit_general(Request $request)
	{
		$id = base64_decode($request->id);
		$product_info = Products::where('id', $id)->first();
		$description_id = base64_decode($request->general_id);
		$description_data = DB::table('product_extra_general')->where('id', $description_id)->first();

		$page_details = array(
			"Title" => "Update general  to " . $product_info->name,
			"Method" => "2",
			"Box_Title" => "Update general  to " . $product_info->name,
			"Action_route" => route('edit_general', [base64_encode($id), base64_encode($description_id)]),
			"back_route" => route('product_general', base64_encode($id)),
			"Form_data" => array(

				"Form_field" => array(

					"text_field" => array(
						'label' => 'Title',
						'type' => 'text',
						'name' => 'title',
						'id' => 'title',
						'classes' => 'form-control',
						'placeholder' => 'Title',
						'value' => $description_data->product_general_descrip_title,
						'disabled' => ''
					),
					"description_field" => array(
						'label' => 'Content',
						'type' => 'textarea',
						'name' => 'content',
						'id' => 'content',
						'classes' => 'form-control',
						'placeholder' => 'Content',
						'value' => $description_data->product_general_descrip_content,
						'disabled' => ''
					),

					"description_file_field" => array(
						'label' => 'Description Image *',
						'type' => 'file_special',
						'name' => 'description_image',
						'id' => 'file-2',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => ''

					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					),
					"images" => array(
						'description_image' => ''
					)
				)
			)
		);

		if ($request->isMethod('post')) {

			$input = $request->all();
			$request->validate([
				'content' => 'max:5000',
				'title' => 'required|max:255'

			]);

			$insert_data = array(
				"product_general_descrip_title" => $input['title'],
				"product_id" => $id,
				"product_general_descrip_content" => $input['content']
			);

			$res = DB::table('product_extra_general')->where('id', $description_id)->update($insert_data);
			if ($res) {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			} else {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			}

			return Redirect::back();
		}
		return view('admin.product.product_general_info.form', ['page_details' => $page_details]);
	}
	public function delete_general(Request $request)
	{
		$id = base64_decode($request->id);
		$res = DB::table('product_extra_general')->where('id', $id)->delete();

		if ($res) {
			MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		} else {
			MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
		}

		return Redirect::back();
	}
	public function add_general(Request $request)
	{
		$id = base64_decode($request->id);
		$product_info = Products::where('id', $id)->first();
		$page_details = array(
			"Title" => "Add general",
			"Method" => "1",
			"Box_Title" => "Add general to " . $product_info->name,
			"Action_route" => route('add_general', base64_encode($id)),
			"back_route" => route('product_general', base64_encode($id)),
			"Form_data" => array(

				"Form_field" => array(

					"text_field" => array(
						'label' => 'Title',
						'type' => 'text',
						'name' => 'title',
						'id' => 'title',
						'classes' => 'form-control',
						'placeholder' => 'Title',
						'value' => '',
						'disabled' => ''
					),
					"description_field" => array(
						'label' => 'Content',
						'type' => 'textarea',
						'name' => 'content',
						'id' => 'content',
						'classes' => 'form-control',
						'placeholder' => 'Content',
						'value' => '',
						'disabled' => ''
					),


					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					),
					"images" => array(
						'description_image' => ''
					)
				)
			)
		);

		if ($request->isMethod('post')) {

			$input = $request->all();
			$request->validate([
				'content' => 'max:5000',
				'title' => 'required|max:255'

			]);

			$insert_data = array(
				"product_general_descrip_title" => $input['title'],
				"product_id" => $id,
				"product_general_descrip_content" => $input['content']
			);


			$res = DB::table('product_extra_general')->insert($insert_data);
			if ($res) {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			} else {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			}

			return Redirect::back();
		}
		return view('admin.product.product_general_info.form', ['page_details' => $page_details]);
	}
	public function product_general(Request $request)
	{
		$prd_id = base64_decode($request->id);
		$product_info = Products::where('id', $prd_id)->first();
		$description_data = DB::table('product_extra_general')->where('product_id', $prd_id)->get();

		if (Auth::guard('vendor')->check()) {
			$route = route('vendor_product');
		} else {
			$route = route('products');
		}

		$page_details = array(
			"Title" => "Product General(s) " . $product_info->name,
			"Box_Title" => "Product General(s) " . $product_info->name,
			"Action_route" => route('product_general', base64_encode($prd_id)),
			"back_route" => $route,
			"reset_route" => ''
		);
		return view('admin.product.product_general_info.list', ['description_data' => $description_data, 'page_details' => $page_details, 'prd_id' => $prd_id]);
	}
	public function productSetting(Request $request)
	{
		$c_id = 5;
		$coupon_assign = DB::table('product_setting')
			->first();

		if ($request->isMethod('post')) {
			$input = $request->all();

			$data = array(
				"product_shows_type" => $input['for_category_or_brand_or_product']
			);


			$coupon_assign = DB::table('product_setting')

				->first();
			if ($coupon_assign) {
				$res = DB::table('product_setting')
					->update($data);
			} else {
				$res = DB::table('product_setting')
					->insert($data);
			}

			if ($res) {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			} else {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			}
			return Redirect::back();
		}
		$page_details = array(
			"Title" => "Product Setting",
			"Box_Title" => "Product Setting",
			"search_route" => '',
			"method" => 0,
			"Action_route" => route('productSetting'),
			"Form_data" => array(

				"Form_field" => array(

					"for_category_or_brand_or_product" => array(
						'label' => 'Product Display Type',
						'type' => 'radio',
						'name' => 'for_category_or_brand_or_product',
						'id' => 'for_category_or_brand_or_product',
						'classes' => ' CouponAssignType',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "0",
								"name" => "Display Multiple Product",
							), (object)array(
								"id" => "1",
								"name" => "Display Single Product",
							)
						),
						'disabled' => '',
						'selected' => ($coupon_assign) ? $coupon_assign->product_shows_type : 0
					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					)
				)
			)
		);
		return view('admin.product.productSetting', ['page_details' => $page_details]);
	}

	public function viewProductImages(Request $request)
	{

		$page_details = array(
			"Title" => "Product Image View",
			"Method" => "1",
			"Box_Title" => "Product Image View",
			"Action_route" => "",
			"Form_data" => array()
		);


		return view('admin.product.image_view', [
			'page_details' => $page_details
		]);
	}


	public function ProductImages(Request $request)
	{

		$page_details = array(
			"Title" => "Product Images",
			"Method" => "1",
			"Box_Title" => "Product Images",
			"Action_route" => "",
			"Form_data" => array()
		);

		return view('admin.product.product_image_view', [
			'page_details' => $page_details
		]);
	}

	public function imageUploads(Request $request)
	{

		$brands = Brands::select('id', 'name')->where('isdeleted', 0)->limit(1)->get();
		$Materials = Materials::select('id', 'name')->where('isdeleted', 0)->limit(1)->get();
		$level = base64_decode($request->level);

		$prd_id = $request->session()->get('product_id');
		$cats = CustomFormHelper::getProductCategory($prd_id);

		$product_type = $request->session()->get('product_type');

		$ProductImages = new ProductImages();
		$ProductAttributes = new ProductAttributes;
		$attr = $ProductAttributes->getProductAttributes($prd_id);
		$colors = $ProductAttributes->getColor_ProductAttributes($prd_id);
		$filters = $ProductAttributes->getCategoryFilter($cats);

		$page_details = array(
			"Title" => "Image Uploads",
			"Method" => "1",
			"Box_Title" => "Image Uploads",
			"Action_route" => route('imageUploads'),
			"Form_data" => array(
				"Form_field" => array(
					"vendor_field" => array(
						'label' => 'Vendor',
						'type' => 'select',
						'name' => 'vendor_field',
						'id' => 'vendor_field',
						'classes' => 'form-control ProductClass',
						'placeholder' => '',
						'value' => [],
						'disabled' => '',
						'selected' => 1
					),

					"product_size_field" => array(
						'label' => 'Size',
						'type' => 'select',
						'name' => 'atr_size[]',
						'id' => 'atr_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($request->session()->get('product_id')),
						'disabled' => '',
						'selected' => ''
					),
					"product_color_field" => array(
						'label' => 'Color',
						'type' => 'select',
						'name' => 'atr_color[]',
						'id' => 'atr_color',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getColors($request->session()->get('product_id')),
						'disabled' => '',
						'selected' => ''
					),
					"product_barcode_field" => array(
						'label' => 'Barcode',
						'type' => 'text',
						'name' => 'barcode[]',
						'id' => 'barcode',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_sku_field" => array(
						'label' => 'Sku',
						'type' => 'text',
						'name' => 'sku[]',
						'id' => 'skuatr',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_qty_field" => array(
						'label' => 'Qty',
						'type' => 'text',
						'name' => 'atr_qty[]',
						'id' => 'atr_qty',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_price_field" => array(
						'label' => 'Price',
						'type' => 'text',
						'name' => 'atr_price[]',
						'id' => 'atr_price',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => 0,
						'disabled' => ''
					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'button',
						'name' => 'submit',
						'id' => 'imageUploads',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					)
				)
			),
			"return_data" => array(
				'attr' => $attr,
				'color_attr' => $colors,
				'prd_id' => $prd_id,
				'image_html' => '',
				'product_images' => array()
			)
		);

		if ($request->isMethod('post')) {


			$input = $request->all();
			$request->validate([

				'images' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
			]);

			if ($request->hasfile('images')) {

				$total = sizeof($request->file('images'));
				$image_cc = 0;
				foreach ($request->file('images') as $image) {
					$destinationPath = Config::get('constants.uploads.product_images');
					$file_name = $input['vendor_field'] . $image->getClientOriginalName();
					$fn = FIleUploadingHelper::BulkUploadIMages($destinationPath, $image, $file_name, 0, 0, true);
					if ($fn != '') {
						$image_cc++;
					}
				}
				if ($total == $image_cc) {
					MsgHelper::save_session_message('success', 'Images Uploaded successfully', $request);
				} else {
					MsgHelper::save_session_message('danger', $image_cc . 'Images Uploaded successfully out of ' . $total, $request);
				}
			}
		}

		return view('admin.product.zip_uploads', ['page_details' => $page_details]);
		exit();
	}
	public function imageUploads1(Request $request)
	{
		$brands = Brands::select('id', 'name')->where('isdeleted', 0)->get();
		$Materials = Materials::select('id', 'name')->where('isdeleted', 0)->get();
		$level = base64_decode($request->level);
		$prd_id = $request->session()->get('product_id');
		$cats = CustomFormHelper::getProductCategory($prd_id);

		$product_type = $request->session()->get('product_type');

		$ProductImages = new ProductImages();
		$ProductAttributes = new ProductAttributes;
		$attr = $ProductAttributes->getProductAttributes($prd_id);
		$colors = $ProductAttributes->getColor_ProductAttributes($prd_id);
		$filters = $ProductAttributes->getCategoryFilter($cats);

		$page_details = array(
			"Title" => "Image imageUploads1",
			"Method" => "1",
			"Box_Title" => "Image imageUploads1",
			"Action_route" => route('imageUploads1'),
			"Form_data" => array(
				"Form_field" => array(


					"product_size_field" => array(
						'label' => 'Size',
						'type' => 'select',
						'name' => 'atr_size[]',
						'id' => 'atr_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($request->session()->get('product_id')),
						'disabled' => '',
						'selected' => ''
					),
					"product_color_field" => array(
						'label' => 'Color',
						'type' => 'select',
						'name' => 'atr_color[]',
						'id' => 'atr_color',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getColors($request->session()->get('product_id')),
						'disabled' => '',
						'selected' => ''
					),
					"product_barcode_field" => array(
						'label' => 'Barcode',
						'type' => 'text',
						'name' => 'barcode[]',
						'id' => 'barcode',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_sku_field" => array(
						'label' => 'Sku',
						'type' => 'text',
						'name' => 'sku[]',
						'id' => 'skuatr',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_qty_field" => array(
						'label' => 'Qty',
						'type' => 'text',
						'name' => 'atr_qty[]',
						'id' => 'atr_qty',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_price_field" => array(
						'label' => 'Price',
						'type' => 'text',
						'name' => 'atr_price[]',
						'id' => 'atr_price',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => 0,
						'disabled' => ''
					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'button',
						'name' => 'submit',
						'id' => 'imageUploads',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					)
				)
			),
			"return_data" => array(
				'attr' => $attr,
				'color_attr' => $colors,
				'prd_id' => $prd_id,
				'image_html' => '',
				'product_images' => array()
			)
		);

		if ($request->isMethod('post')) {


			$input = $request->all();
			$request->validate([

				'images' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
			]);

			if ($request->hasfile('images')) {

				$total = sizeof($request->file('images'));
				file_put_contents("images.txt", $total);
				$image_cc = 0;
				foreach ($request->file('images') as $image) {
					$destinationPath = Config::get('constants.uploads.product_images1');
					$file_name = $image->getClientOriginalName();
					$fn = FIleUploadingHelper::BulkUploadIMages($destinationPath, $image, $file_name, 0, 0, false);
					if ($fn != '') {
						$image_cc++;
					}
				}
				if ($total == $image_cc) {
					MsgHelper::save_session_message('success', 'Images Uploaded successfully', $request);
				} else {
					MsgHelper::save_session_message('danger', $image_cc . 'Images Uploaded successfully out of ' . $total, $request);
				}
			}
		}

		return view('admin.product.image_uploads1', [
			'page_details' => $page_details
		]);
	}
	public function edit_description(Request $request)
	{
		$id = base64_decode($request->id);
		$product_info = Products::where('id', $id)->first();
		$description_id = base64_decode($request->description_id);
		$description_data = DB::table('product_extra_description')->where('id', $description_id)->first();

		$page_details = array(
			"Title" => "Update Extra Description(s)",
			"Method" => "2",
			"Box_Title" => "Update Extra Description to " . $product_info->name,
			"Action_route" => route('edit_description', [base64_encode($id), base64_encode($description_id)]),
			"back_route" => route('product_description', base64_encode($id)),
			"Form_data" => array(

				"Form_field" => array(

					"text_field" => array(
						'label' => 'Title',
						'type' => 'text',
						'name' => 'title',
						'id' => 'title',
						'classes' => 'form-control',
						'placeholder' => 'Title',
						'value' => $description_data->product_descrip_title,
						'disabled' => ''
					),
					"description_field" => array(
						'label' => 'Content',
						'type' => 'textarea',
						'name' => 'content',
						'id' => 'content',
						'classes' => 'form-control',
						'placeholder' => 'Content',
						'value' => $description_data->product_descrip_content,
						'disabled' => ''
					),

					"description_file_field" => array(
						'label' => 'Description Image *',
						'type' => 'file_special_imagepreview',
						'name' => 'description_image',
						'id' => 'file-2',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => '',
						'onchange' => 'image_preview(event)'

					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					),
					"images" => array(
						'description_image' => $description_data->product_descrip_image
					)
				)
			)
		);

		if ($request->isMethod('post')) {

			$input = $request->all();
			$request->validate([
				'content' => 'max:5000',
				'title' => 'required|max:255',
				'description_image' => 'mimes:jpeg,bmp,png'

			]);

			$insert_data = array(
				"product_descrip_title" => $input['title'],
				"product_id" => $id,
				"product_descrip_content" => $input['content']
			);

			if ($request->hasFile('description_image')) {

				$banner_image = $request->file('description_image');
				$destinationPath2 =  Config::get('constants.uploads.product_images');
				$file_name2 = $banner_image->getClientOriginalName();

				$file_name2 = FIleUploadingHelper::UploadImage($destinationPath2, $banner_image, $file_name2, 0, 0, true);
				if ($file_name2 == '') {
					MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
					return Redirect::back();
				}

				$insert_data = array(
					"product_descrip_title" => $input['title'],
					"product_id" => $id,
					"product_descrip_content" => $input['title'],
					"product_descrip_image" => $file_name2,
				);
			}
			$res = DB::table('product_extra_description')->where('id', $description_id)->update($insert_data);
			if ($res) {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			} else {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			}

			return Redirect::back();
		}
		return view('admin.product.product_description.form', ['page_details' => $page_details]);
	}
	public function delete_description(Request $request)
	{
		$id = base64_decode($request->id);
		$res = DB::table('product_extra_description')->where('id', $id)->delete();

		if ($res) {
			MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		} else {
			MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
		}

		return Redirect::back();
	}
	public function add_description(Request $request)
	{
		$id = base64_decode($request->id);
		$product_info = Products::where('id', $id)->first();
		$page_details = array(
			"Title" => "Add Extra Description",
			"Method" => "1",
			"Box_Title" => "Add Extra Description to " . $product_info->name,
			"Action_route" => route('add_description', base64_encode($id)),
			"back_route" => route('product_description', base64_encode($id)),
			"Form_data" => array(

				"Form_field" => array(

					"text_field" => array(
						'label' => 'Title',
						'type' => 'text',
						'name' => 'title',
						'id' => 'title',
						'classes' => 'form-control',
						'placeholder' => 'Title',
						'value' => '',
						'disabled' => ''
					),
					"description_field" => array(
						'label' => 'Content',
						'type' => 'textarea',
						'name' => 'content',
						'id' => 'content',
						'classes' => 'form-control',
						'placeholder' => 'Content',
						'value' => '',
						'disabled' => ''
					),

					"description_file_field" => array(
						'label' => 'Description Image *',
						'type' => 'file_special_imagepreview',
						'name' => 'description_image',
						'id' => 'file-2',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => '',
						'onchange' => 'image_preview(event)'

					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					),
					"images" => array(
						'description_image' => ''
					)
				)
			)
		);

		if ($request->isMethod('post')) {

			$input = $request->all();
			$request->validate([
				'content' => 'max:5000',
				'title' => 'required|max:255',
				'description_image' => 'mimes:jpeg,bmp,png'

			]);

			$insert_data = array(
				"product_descrip_title" => $input['title'],
				"product_id" => $id,
				"product_descrip_content" => $input['content'],
				"product_descrip_image" => "",
			);

			if ($request->hasFile('description_image')) {
				$banner_image = $request->file('description_image');
				$destinationPath2 =  Config::get('constants.uploads.product_images');
				$file_name2 = $banner_image->getClientOriginalName();

				$file_name2 = FIleUploadingHelper::UploadImage($destinationPath2, $banner_image, $file_name2, 0, 0, true);
				if ($file_name2 == '') {
					MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
					return Redirect::back();
				}
				$insert_data['product_descrip_image'] = $file_name2;
			}
			$res = DB::table('product_extra_description')->insert($insert_data);
			if ($res) {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			} else {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			}

			return Redirect::back();
		}
		return view('admin.product.product_description.form', ['page_details' => $page_details]);
	}
	public function product_description(Request $request)
	{
		$prd_id = base64_decode($request->id);
		$product_info = Products::where('id', $prd_id)->first();
		$description_data = DB::table('product_extra_description')->where('product_id', $prd_id)->get();

		if (Auth::guard('vendor')->check()) {
			$route = route('vendor_product');
		} else {
			$route = route('products');
		}

		$page_details = array(
			"Title" => "Product Description(s) " . $product_info->name,
			"Box_Title" => "Product Description(s) " . $product_info->name,
			"Action_route" => route('product_description', base64_encode($prd_id)),
			"back_route" => $route,
			"reset_route" => ''
		);
		return view('admin.product.product_description.list', ['description_data' => $description_data, 'page_details' => $page_details, 'prd_id' => $prd_id]);
	}

	public function qa(Request $request)
	{
		$prd_id = base64_decode($request->id);

		if (Auth::guard('vendor')->check()) {
			$route = route('vendor_product');
		} else {
			$route = route('products');
		}
		$page_details = array(
			"Title" => "QA List",
			"Box_Title" => "QA(s)",
			"search_route" => '',
			"reset_route" => '',
			"back_route" => $route,

		);
		$qas = DB::table('product_question_answer')->where('product_id', $prd_id)->paginate(50);
		return view('admin.product.qa.list', ['qas' => $qas, 'page_details' => $page_details, 'prd_id' => $prd_id]);
	}

	public function add_qa(Request $request)
	{

		$prd_id = base64_decode($request->id);
		$page_details = array(
			"Title" => "Add QA",
			"Method" => "1",
			"Box_Title" => "Add QA",
			"Action_route" => route('add_qa', (base64_encode($prd_id))),
			"back_route" => route('qa', (base64_encode($prd_id))),
			"Form_data" => array(

				"Form_field" => array(

					"question_field" => array(
						'label' => 'Question',
						'type' => 'text',
						'name' => 'question',
						'id' => 'question',
						'classes' => 'form-control',
						'placeholder' => 'Question',
						'value' => '',
						'disabled' => ''
					),
					"answer_field" => array(
						'label' => 'Answer',
						'type' => 'text',
						'name' => 'answer',
						'id' => 'answer',
						'classes' => 'form-control',
						'placeholder' => 'Answer',
						'value' => '',
						'disabled' => ''
					),

					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					),

				)
			)
		);

		if ($request->isMethod('post')) {

			$input = $request->all();
			$request->validate([
				'question' => 'required|max:255',
				'answer' => 'required|max:255',


			]);


			$error = DB::table('product_question_answer')->insert(
				[
					'product_question' => $input['question'],
					'product_answer' => $input['answer'],
					"product_id" => $prd_id
				]
			);

			/* save the following details */
			if ($error == 0) {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			} else {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			}
			return Redirect::back();
		}
		return view('admin.product.qa.form', ['page_details' => $page_details]);
	}

	public function edit_qa(Request $request)
	{

		$qa_id = base64_decode($request->id);

		$res = DB::table('product_question_answer')->where('id', $qa_id)->first();
		$page_details = array(
			"Title" => "Update QA",
			"Method" => "1",
			"Box_Title" => "Update QA",
			"back_route" => route('qa', (base64_encode($res->product_id))),
			"Action_route" => route('edit_qa', (base64_encode($qa_id))),
			"Form_data" => array(

				"Form_field" => array(

					"question_field" => array(
						'label' => 'Question',
						'type' => 'text',
						'name' => 'question',
						'id' => 'question',
						'classes' => 'form-control',
						'placeholder' => 'Question',
						'value' => $res->product_question,
						'disabled' => ''
					),
					"answer_field" => array(
						'label' => 'Answer',
						'type' => 'text',
						'name' => 'answer',
						'id' => 'answer',
						'classes' => 'form-control',
						'placeholder' => 'Answer',
						'value' => $res->product_answer,
						'disabled' => ''
					),

					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					),

				)
			)
		);

		if ($request->isMethod('post')) {

			$input = $request->all();
			$request->validate([
				'question' => 'required|max:255',
				'answer' => 'required|max:255',


			]);


			$error = DB::table('product_question_answer')
				->where('id', $qa_id)
				->update(
					[
						'product_question' => $input['question'],
						'product_answer' => $input['answer']
					]
				);

			/* save the following details */
			if ($error == 0) {
				MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
			} else {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			}
			return Redirect::back();
		}

		return view('admin.product.qa.form', ['page_details' => $page_details]);
	}
	public function delete_qa(Request $request)
	{
		$qa_id = base64_decode($request->id);



		$res = DB::table('product_question_answer')->where('id', $qa_id)
			->delete();
		if ($res) {
			MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		} else {
			MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
		}

		return Redirect::back();
	}

	public function qa_sts(Request $request)
	{
		$id = base64_decode($request->id);
		$sts = base64_decode($request->sts);

		$res = DB::table('product_question_answer')->where('id', $id)
			->update(['product_question_answer_status' => ($sts == 0) ? 1 : 0]);

		if ($res) {
			MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		} else {
			MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
		}

		return Redirect::back();
	}
	public function productSelection(Request $request)
	{
		$inputs = $request->all();


		$request->session()->put('product_id', 0);
		$request->session()->put('product_type', array(
			array(
				"product_type" =>	$inputs['product_type'],
				"vendor" =>	$inputs['product_vendor'],
				'product_id' => 0
			)
		));

		return redirect()->route('add_product', ['level' => base64_encode(0)]);
	}

	public function index(Request $request)
	{




		$vendors = Vendor::select('id', 'username as name', 'email', 'registration_id')->where('isdeleted', '=', '0')->where('status', '=', '1')->get();

		$Brands = Brands::where('isdeleted', 0);
		$Brands = $Brands->orderBy('id', 'DESC')->paginate(100);
		$parameters = $request->str;

		if ($parameters != '') {
			$export = route('exportProduct_with_Search', ($request->str));
		} else {
			$export = route('exportProduct');
		}
		$backroute = '';

		if (Auth::guard('vendor')->check()) {
			$backroute = route('vendor_product');
		} else {
			$backroute = route('products');
		}

		$page_details = array(
			"Title" => "Product List",
			"Box_Title" => "Product(s)",
			"search_route" => URL::to('admin/filters_products'),
			'back_route' => $backroute,
			"reset_route" => route('products'),
			"export" => $export,
			"Form_data" => array(
				"Form_field" => array(
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger disableAfterClick',
						'placeholder' => '',
						'value' => 'Save'
					),


					"select_field" => array(
						'label' => 'Select Parent',
						'type' => 'select_with_inner_loop_for_filter',
						'name' => 'category_id',
						'id' => 'category_id',
						'classes' => 'custom-select form-control category_id',
						'placeholder' => 'Name',
						'value' => CommonHelper::getAdminChilds(1, '', old('parent_id'))
					)
				)
			)
		);

		$Products = Products::select('products.*');

		if ($parameters != '') {
			/*$Products=$Products->where('products.name','LIKE',$parameters.'%');*/
			$Products = $Products
				->join('product_categories', 'products.id', '=', 'product_categories.product_id')
				->join('categories', 'product_categories.product_id', '=', 'categories.id')
				->where('products.isexisting', 0)
				->Where(function ($query) use ($parameters) {
					$query->orWhere('products.name', 'regexp', $parameters);
					$query->orWhere('products.sku', 'regexp',  $parameters);
					$query->orWhere('categories.name', 'regexp', $parameters);
					$query->orWhere('products.id', 'regexp',  $parameters);
				});
		}
		/*$products=$Products->orderBy('id', 'DESC')->where('products.name','LIKE',$parameters.'%')->paginate(100);*/

		$products = $Products->where('products.isdeleted', 0)->groupBy('products.id')->orderBy('products.id', 'DESC')->paginate(100);

		foreach ($products as $newProducts) {

			$vendors_details = Vendor::select('username as uname')->where('id', '=', $newProducts->vendor_id)->first();

			if ($vendors_details) {
				$newProducts->uname = $vendors_details->uname;
			} else {
				$newProducts->uname = '';
			}
		}

		return view('admin.product.list', ['products' => $products, 'page_details' => $page_details, 'vendors' => $vendors, 'Brands' => $Brands]);
	}

	public function filters_products(Request $request)
	{
		$vendors = Vendor::select('id', 'username as name', 'email')->where('isdeleted', '=', '0')->where('status', '=', '1')->get();
		$Brands = Brands::where('isdeleted', 0);
		$Brands = $Brands->orderBy('id', 'DESC')->paginate(100);

		$parameters = $request->str;
		$sts = $request->sts;
		$vendor = $request->vendor;
		$type = $request->type;
		$category_id = $request->category_id;
		$blocked = $request->blocked;
		$brands = $request->brands;

		$export = route('exportProduct_with_Search1', [
			$vendor,
			$sts,
			$parameters,
			$type,
			$category_id,
			$brands,
			$blocked
		]);




		if (Auth::guard('vendor')->check()) {
			$backroute = route('vendor_product');
		} else {
			$backroute = route('products');
		}

		$page_details = array(
			"Title" => "Product List",
			"Box_Title" => "Product(s)",
			"search_route" => URL::to('admin/filters_products'),
			"reset_route" => route('products'),
			'back_route' => $backroute,
			"export" => $export,
			"Form_data" => array(
				"Form_field" => array(
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger disableAfterClick',
						'placeholder' => '',
						'value' => 'Save'
					),
					"select_field" => array(
						'label' => 'Select Parent',
						'type' => 'select_with_inner_loop_for_filter',
						'name' => 'category_id',
						'id' => 'category_id',
						'classes' => 'custom-select form-control category_id',
						'placeholder' => 'Name',
						'value' => CommonHelper::getAdminChilds(1, '', ($category_id != 'All') ? $category_id : 0)
					)
				)
			)
		);

		//$Products= Products::select('products.*')->where('products.isdeleted', 0);
		$Products = Products::select('products.*');

		// if( ($category_id!='All' && $category_id!='')  || ($parameters!='All' && $parameters!='')){
		if ((($parameters != 'All' && $parameters != '') || $category_id != 'All' && $category_id != '')) {

			$Products = $Products
				->join('product_categories', 'product_categories.product_id', '=', 'products.id')
				->join('categories', 'categories.id', '=', 'product_categories.cat_id');
		}

		if ($brands != 'All' &&  $brands != '') {
			$selcted_brand = explode(",", $brands);

			$Products = $Products->whereIn('products.product_brand', $selcted_brand);
		}


		if ($vendor != 'All' && $vendor != '') {
			$selcted_vendor = explode(",", $vendor);
			$Products = $Products->whereIn('products.vendor_id', $selcted_vendor);
		}

		if ($sts != '' && $sts != 'All') {
			$Products = $Products->where('products.status', '=', $sts);
		}

		if ($blocked != '' && $blocked != 'All') {
			$Products = $Products->where('products.isblocked', '=', $blocked);
		}

		if ($type != 'All' && $type != '') {
			///$Products=$Products->where('products.isexisting','=',$type);
		}


		if ($parameters != 'All' && $parameters != '') {
			$Products = $Products
				->Where(function ($query) use ($parameters) {
					$query->orWhere('products.name', 'LIKE', '%' . $parameters . '%');
					//$query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
					$query->orWhere('categories.name', 'LIKE', '%' . $parameters . '%');
					$query->orWhere('products.id', 'LIKE', '%' . $parameters . '%');
				});
		}
		if ($category_id != 'All' && $category_id != '') {
			$Products = $Products->where('product_categories.cat_id', $category_id);
		}


		$products = $Products->where('products.isdeleted', 0)->groupBy('products.id')->orderBy('products.id', 'DESC')->paginate(100);


		foreach ($products as $newProducts) {

			$vendors_details = Vendor::select('username as uname')->where('id', '=', $newProducts->vendor_id)->first();

			if ($vendors_details) {
				$newProducts->uname = $vendors_details->uname;
			} else {
				$newProducts->uname = '';
			}
		}



		return view('admin.product.list', ['products' => $products, 'page_details' => $page_details, 'vendors' => $vendors, 'Brands' => $Brands]);
	}

	public function multi_delete_product(Request $request)
	{
		$input = $request->all();
		Products::whereIn('id', $input['product_id'])
			->update([
				'isdeleted' => 1
			]);

		DB::table('product_home_slider')->whereIn('product_id', $input['product_id'])->delete();

		MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);

		if (Auth::guard('vendor')->check()) {
			return redirect()->route('vendor_product');
		} else {
			return redirect()->route('products');
		}
	}

	public function bl_nblk_Product(Request $request) //block and unblock product
	{
		$input = $request->all();


		Products::where('id', $input['product_id'])
			->update([
				'isblocked' => $input['method']
			]);

		DB::table('blocked_product_log')->insert(
			array(
				"product_id" => $input['product_id'],
				"reason" => $input['reason'],
				"mode" => $input['method']
			)
		);

		MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);

		return redirect()->back();
	}
	public function exportProduct(Request $request)
	{
		$str = $request->str;
		$sts = $request->sts;
		$vendor = $request->vendor;
		$type = $request->type;
		$category_id = $request->category_id;
		$blocked = $request->blocked;
		$brands = $request->brands;

		return Excel::download(new ProductExport($str, $sts, $vendor, $type, $category_id, $brands, $blocked), 'Products' . date('d-m-Y H:i:s') . '.csv');
	}

	public function add(Request $request)
	{
		$brands = Brands::select('id', 'name')->where('isdeleted', 0)->get();
		$Materials = Materials::select('id', 'name')->where('isdeleted', 0)->get();
		$level = base64_decode($request->level);



		$sess_data = $request->session()->get('product_type');

		$product_type = $sess_data[0]['product_type'];
		$prd_id = $sess_data[0]['product_id'];
		$cats = CustomFormHelper::getProductCategory($prd_id);
		$ProductImages = new ProductImages();
		$ProductAttributes = new ProductAttributes;
		$attr = $ProductAttributes->getProductAttributes($prd_id);
		$colors = $ProductAttributes->getColor_ProductAttributes($prd_id);
		$filters = $ProductAttributes->getCategoryFilter($cats);

		if (Auth::guard('vendor')->check()) {
			$backroute = route('vendor_product');
		} else {
			$backroute = route('products');
		}
		$page_details = array(
			"Title" => "Add Product",
			"Method" => "1",
			"Box_Title" => "Add Product",
			"Action_route" => route('add_product', (base64_encode($level))),
			'back_route' => $backroute,
			"Form_data" => array(
				"Form_field" => array(
					"product_name_field" => array(
						'label' => 'Product Name *',
						'type' => 'text',
						'name' => 'name',
						'id' => 'name',
						'classes' => 'form-control',
						'placeholder' => 'Name',
						'value' => old('name'),
						'disabled' => ''
					),
					"product_gtin_field" => array(
						'label' => 'Product GTIN *',
						'type' => 'text',
						'name' => 'gtin',
						'id' => 'gtin',
						'classes' => 'form-control',
						'placeholder' => 'GTIN',
						'value' => old('gtin'),
						'disabled' => ''
					),
					"product_short_description_field" => array(
						'label' => 'Short Description *',
						'type' => 'textarea',
						'name' => 'short_description',
						'id' => 'short_description',
						'classes' => 'ckeditor form-control',
						'placeholder' => 'Short description',
						'value' => old('short_description'),
						'disabled' => ''
					),
					"product_long_description_field" => array(
						'label' => 'Long Description *',
						'type' => 'textarea',
						'name' => 'long_description',
						'id' => 'long_description',
						'classes' => 'ckeditor form-control',
						'placeholder' => 'Long description',
						'value' => old('long_description'),
						'disabled' => ''
					),
					"additional_Information_field" => array(
						'label' => 'Additional Information Description *',
						'type' => 'textarea',
						'name' => 'additional_Information',
						'id' => 'additional_Information',
						'classes' => 'ckeditor form-control',
						'placeholder' => 'Additional Information description',
						'value' => old('additional_Information'),
						'disabled' => ''
					),

					"product_sku_field" => array(
						'label' => 'SKU *',
						'type' => 'text',
						'name' => 'sku',
						'id' => 'sku',
						'classes' => 'form-control',
						'placeholder' => 'sku',
						'value' => old('sku'),
						'disabled' => ''
					),
					"product_code_field" => array(
						'label' => 'GTIN *',
						'type' => 'text',
						'name' => 'product_code',
						'id' => 'product_code',
						'classes' => 'form-control',
						'placeholder' => '123',
						'value' => old('product_code'),
						'disabled' => ''
					),
					"product_weight_field" => array(
						'label' => 'Weight (In GM)',
						'type' => 'number',
						'name' => 'weight',
						'id' => 'weight',
						'classes' => 'form-control ',
						'placeholder' => 'weight',
						'value' => old('weight'),
						'disabled' => ''
					),
					"product_height_field" => array(
						'label' => 'Height (In CM)',
						'type' => 'number',
						'name' => 'height',
						'id' => 'height',
						'classes' => 'form-control ',
						'placeholder' => 'height',
						'value' => old('height'),
						'disabled' => ''
					),
					"product_length_field" => array(
						'label' => 'Length (In CM)',
						'type' => 'number',
						'name' => 'length',
						'id' => 'length',
						'classes' => 'form-control ',
						'placeholder' => 'Length',
						'value' => old('length'),
						'disabled' => ''
					),
					"product_width_field" => array(
						'label' => 'Width (In CM)',
						'type' => 'number',
						'name' => 'width',
						'id' => 'width',
						'classes' => 'form-control ',
						'placeholder' => 'width',
						'value' => old('width'),
						'disabled' => ''
					),
					"product_status_field" => array(
						'label' => 'Status *',
						'type' => 'select',
						'name' => 'status',
						'id' => 'status',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Enabled",
							), (object)array(
								"id" => "0",
								"name" => "Disabled",
							)
						),
						'disabled' => '',
						'selected' => old('status')
					),
					"product_hsn_field" => array(
						'label' => 'HSN CODE',
						'type' => 'text',
						'name' => 'hsn_code',
						'id' => 'hsn_code',
						'classes' => 'form-control',
						'placeholder' => '123',
						'value' => old('hsn_code'),
						'disabled' => ''
					),
					"product_tax_field" => array(
						'label' => 'Tax Class *',
						'type' => 'select',
						'name' => 'tax_class',
						'id' => 'tax_class',
						'classes' => 'form-control',
						'placeholder' => 'Tax',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "None",
							), (object)array(
								"id" => "0",
								"name" => "Taxable Goods",
							)
						),
						'disabled' => '',
						'selected' => old('tax_class')
					),
					"product_visibility_field" => array(
						'label' => 'Visibility',
						'type' => 'select',
						'name' => 'visibility',
						'id' => 'visibility',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Not visible individualy",
							), (object)array(
								"id" => "2",
								"name" => "Catalog",
							), (object)array(
								"id" => "3",
								"name" => "Search",
							), (object)array(
								"id" => "4",
								"name" => "Catalog,Search",
							)
						),
						'disabled' => '',
						'selected' => old('visibility')
					),
					"product_image_field" => array(
						'label' => 'Default Image *',
						'type' => 'file_special_imagepreview',
						'name' => 'default_image',
						'id' => 'file-1',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => '',
						'disabled' => '',
						'selected' => '',
						'onchange' => 'image_preview(event)'
					),
					"product_zoomimage_field" => array(
						'label' => 'Hover/Zoom Image',
						'type' => 'file_special_imagepreview',
						'name' => 'zoom_image',
						'id' => 'file-2',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => '',
						'disabled' => '',
						'selected' => '',
						'onchange' => 'image_preview(event)'
					),
					"product_price_field" => array(
						'label' => 'MRP',
						'type' => 'text',
						'name' => 'price',
						'id' => 'price',
						'classes' => 'form-control',
						'placeholder' => 'Price',
						'value' => old('price'),
						'disabled' => ''
					),
					"product_spcl_price_field" => array(
						'label' => 'Listing Price *',
						'type' => 'text',
						'name' => 'spcl_price',
						'id' => 'spcl_price',
						'classes' => 'form-control',
						'placeholder' => 'Special Price',
						'value' => old('spcl_price'),
						'disabled' => ''
					),
					"product_spcl_from_date_field" => array(
						'label' => 'Special Price From Date',
						'type' => 'date',
						'name' => 'spcl_from_date',
						'id' => 'spcl_from_date',
						'classes' => 'datepicker form-control',
						'placeholder' => '',
						'value' => old('spcl_from_date'),
						'disabled' => ''
					),
					"product_spcl_to_date_field" => array(
						'label' => 'Special Price To Date',
						'type' => 'date',
						'name' => 'spcl_to_date',
						'id' => 'spcl_to_date',
						'classes' => 'datepicker form-control',
						'placeholder' => '',
						'value' => old('spcl_to_date'),
						'disabled' => ''
					),
					"product_meta_title_field" => array(
						'label' => 'Meta Title',
						'type' => 'text',
						'name' => 'meta_title',
						'id' => 'meta_title',
						'classes' => 'form-control meta_tile',
						'placeholder' => 'Meta Title',
						'value' => old('meta_title'),
						'disabled' => ''
					),
					"product_meta_description_field" => array(
						'label' => 'Meta Description',
						'type' => 'textarea',
						'name' => 'meta_description',
						'id' => 'meta_description',
						'classes' => 'form-control meta_description',
						'placeholder' => 'Meta description',
						'value' => old('meta_description'),
						'disabled' => ''
					),
					"product_meta_keyword_field" => array(
						'label' => 'Meta Keyword',
						'type' => 'textarea',
						'name' => 'meta_keyword',
						'id' => 'meta_keyword',
						'classes' => 'form-control meta_keyword',
						'placeholder' => 'Meta Keyword',
						'value' => old('meta_keyword'),
						'disabled' => ''
					),
					"product_manage_stock_field" => array(
						'label' => 'Manage Stock',
						'type' => 'select',
						'name' => 'manage_stock',
						'id' => 'manage_stock',
						'classes' => 'form-control',
						'placeholder' => 'manage_stock',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "NO",
							)
						),
						'disabled' => '',
						'selected' => old('manage_stock')
					),
					"product_qty_field" => array(
						'label' => 'Qty',
						'type' => 'number',
						'name' => 'qty',
						'id' => 'qty',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => old('qty'),
						'disabled' => ''
					),
					"product_barcode_field" => array(
						'label' => 'Barcode',
						'type' => 'text',
						'name' => 'barcode[]',
						'id' => 'barcode',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_qty_for_out_stock_field" => array(
						'label' => "Qty for Item to be notify",
						'type' => 'number',
						'name' => 'qty_out',
						'id' => 'qty_out',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => old('qty_out'),
						'disabled' => ''
					),
					"product_deleivery_days_field" => array(
						'label' => "Delivery Days",
						'type' => 'number',
						'name' => 'delivery_days',
						'id' => 'delivery_days',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => old('delivery_days'),
						'disabled' => ''
					),
					"product_return_days_field" => array(
						'label' => "Return Days",
						'type' => 'number',
						'name' => 'return_days',
						'id' => 'return_days',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => old('return_days'),
						'disabled' => ''
					),
					"product_shipping_charge_stock_field" => array(
						'label' => "Shipping Charges",
						'type' => 'number',
						'name' => 'shipping_charge',
						'id' => 'shipping_charge',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => old('shipping_charge'),
						'disabled' => ''
					),
					"product_rewards_point_field" => array(
						'label' => "BTA point",
						'type' => 'number',
						'name' => 'rewards_point',
						'id' => 'rewards_point',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => old('rewards_point'),
						'disabled' => ''
					),

					"product_product_range_field" => array(
						'label' => "Range1",
						'type' => 'text',
						'name' => 'product_range1',
						'id' => 'product_range1',
						'classes' => 'form-control',
						'placeholder' => 'Product Range',
						'value' => old('product_range'),
						'disabled' => ''
					),
					"product_product_range_price_field" => array(
						'label' => "Range Price1",
						'type' => 'text',
						'name' => 'product_price1',
						'id' => 'product_price1',
						'classes' => 'form-control',
						'placeholder' => 'Product Price',
						'value' => old('product_price'),
						'disabled' => ''
					),


					"product_product_range_field2" => array(
						'label' => "Range2",
						'type' => 'text',
						'name' => 'product_range2',
						'id' => 'product_range2',
						'classes' => 'form-control',
						'placeholder' => 'Product Range',
						'value' => old('product_range'),
						'disabled' => ''
					),
					"product_product_range_price_field2" => array(
						'label' => "Range Price2",
						'type' => 'text',
						'name' => 'product_price2',
						'id' => 'product_price2',
						'classes' => 'form-control',
						'placeholder' => 'Product Price',
						'value' => old('product_price'),
						'disabled' => ''
					),


					"product_product_range_field3" => array(
						'label' => "Range3",
						'type' => 'text',
						'name' => 'product_range3',
						'id' => 'product_range3',
						'classes' => 'form-control',
						'placeholder' => 'Product Range',
						'value' => old('product_range'),
						'disabled' => ''
					),
					"product_product_range_price_field3" => array(
						'label' => "Range Price3",
						'type' => 'text',
						'name' => 'product_price3',
						'id' => 'product_price3',
						'classes' => 'form-control',
						'placeholder' => 'Product Price',
						'value' => old('product_price'),
						'disabled' => ''
					),
					"product_bar_code_field" => array(
						'label' => "Product Bar Code(SPCL Char not allowed)",
						'type' => 'text',
						'name' => 'bar_code',
						'id' => 'bar_code',
						'classes' => 'form-control',
						'placeholder' => 'Bar Code',
						'value' => '',
						'disabled' => ''
					),
					"product_stock_availability_field" => array(
						'label' => 'Stock Availability',
						'type' => 'select',
						'name' => 'stock_availability',
						'id' => 'stock_availability',
						'classes' => 'form-control',
						'placeholder' => 'stock_availability',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "In Stock",
							), (object)array(
								"id" => "0",
								"name" => "Out Of Stock",
							)
						),
						'disabled' => '',
						'selected' => old('stock_availability')
					),
					"product_material_field" => array(
						'label' => 'Material',
						'type' => 'select',
						'name' => 'material',
						'id' => 'Material',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $Materials,
						'disabled' => '',
						'selected' => old('material')
					),
					"product_brands_field" => array(
						'label' => 'Brands',
						'type' => 'select',
						'name' => 'product_brand',
						'id' => 'product_brands_field',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $brands,
						'disabled' => '',
						'selected' => ''
					),
					"product_size_field" => array(
						'label' => 'Size',
						'type' => 'select',
						'name' => 'atr_size[]',
						'id' => 'atr_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($request->session()->get('product_id')),
						'disabled' => '',
						'selected' => ''
					),
					"product_color_field" => array(
						'label' => 'Color',
						'type' => 'select',
						'name' => 'atr_color[]',
						'id' => 'atr_color',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getColors($request->session()->get('product_id')),
						'disabled' => '',
						'selected' => ''
					),
					"product_atr_qty_field" => array(
						'label' => 'Qty',
						'type' => 'text',
						'name' => 'atr_qty[]',
						'id' => 'atr_qty',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_price_field" => array(
						'label' => 'Price',
						'type' => 'text',
						'name' => 'atr_price[]',
						'id' => 'atr_price',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => 0,
						'disabled' => ''
					),
					"product_atr_sku_field" => array(
						'label' => 'Sku',
						'type' => 'text',
						'name' => 'atr_sku[]',
						'id' => 'atr_sku',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),

					"product_legal_country_of_manufacturing" => array(
						'label' => "Country of Manufacturing",
						'type' => 'text',
						'name' => 'country_of_manufacturing',
						'id' => 'country_of_manufacturing',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),

					"product_legal_manufacturer_name" => array(
						'label' => "Manufacturer's Name",
						'type' => 'text',
						'name' => 'manufacturer_name',
						'id' => 'manufacturer_name',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),

					"product_legal_packer_name" => array(
						'label' => "Packer's Name",
						'type' => 'text',
						'name' => 'packer_name',
						'id' => 'packer_name',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),

					"product_legal_generic_name" => array(
						'label' => "Generic Name",
						'type' => 'text',
						'name' => 'generic_name',
						'id' => 'generic_name',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),


					"product_related_is_shown_field" => array(
						'label' => '',
						'type' => 'select',
						'name' => 'is_related_product_shown',
						'id' => 'is_related_product_shown',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "No",
							)
						),
						'disabled' => '',
						'selected' => old('is_related_product_shown')
					),
					"product_up_sell_is_shown_field" => array(
						'label' => '',
						'type' => 'select',
						'name' => 'is_up_sell_product_shown',
						'id' => 'is_up_sell_product_shown',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "No",
							)
						),
						'disabled' => '',
						'selected' => old('is_up_sell_product_shown')
					),
					"product_cross_sell_is_shown_field" => array(
						'label' => '',
						'type' => 'select',
						'name' => 'is_cross_sell_product_shown',
						'id' => 'is_cross_sell_product_shown',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "No",
							)
						),
						'disabled' => '',
						'selected' => old('is_cross_sell_product_shown')
					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					)
				)
			),
			"return_data" => array(
				'attr' => $attr,
				'color_attr' => $colors,
				'prd_id' => $prd_id,
				'image_html' => '',
				'product_images' => array()
			)
		);

		if ($request->isMethod('post')) {
			$input = $request->all();

			switch ($level) {
				case 0;  // general info tab
					$request->validate([
						// 'name' => 'required|unique:products,name,1,isdeleted|max:500',
						'name' => 'required|max:500',
						//'gtin' => 'required|unique:products,gtin,1,isdeleted|max:500',
						'short_description' => 'required|max:5000',
						// 'sku' => 'required|max:25',
						// 'product_code' => 'required|max:25',
						'weight' => 'max:200|not_in:0',
						'height' => 'max:200|not_in:0',
						'length' => 'max:200|not_in:0',
						'width' => 'max:200|not_in:0',

						'status' => 'required|max:25',
						// 'default_image' => 'required|min:'.Config::get('constants.size.product_img_min').'|max:'.Config::get('constants.size.product_img_max').'',
						//  'zoom_image' => 'required|min:'.Config::get('constants.size.product_img_min').'|max:'.Config::get('constants.size.product_img_max').''
					]);

					if ($request->hasFile('default_image')) {
						$defualt_image = $request->file('default_image');
						$destinationPath = Config::get('constants.uploads.product_images');
						$file_name = $defualt_image->getClientOriginalName();
						$img_extension = $defualt_image->getClientOriginalExtension();

						if ($img_extension == 'gif') {
							//--- uploading gif image --//
							$file_name = FIleUploadingHelper::UploadGifImage($destinationPath, $defualt_image, $file_name);
						} else {

							$file_name = FIleUploadingHelper::UploadImage($destinationPath, $defualt_image, $file_name, 0, 0, true);
						}

						if ($file_name == '') {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
							return Redirect::back();
						} else {
							$input['default_image'] = $file_name;
						}
					}

					if ($request->hasFile('zoom_image')) {
						$defualt_image = $request->file('zoom_image');
						$destinationPath = Config::get('constants.uploads.product_images');
						$file_name = $defualt_image->getClientOriginalName();
						$img_extension = $defualt_image->getClientOriginalExtension();

						if ($img_extension == 'gif') {
							//--- uploading gif image --//
							$file_name = FIleUploadingHelper::UploadGifImage($destinationPath, $defualt_image, $file_name);
						} else {

							$file_name = FIleUploadingHelper::UploadImage($destinationPath, $defualt_image, $file_name, 0, 0, true);
						}

						if ($file_name == '') {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
							return Redirect::back();
						} else {
							$input['zoom_image'] = $file_name;
						}
					}
					$Products = new Products;
					$Products->name = $input['name'];

					$Products->short_description = $input['short_description'];
					$Products->long_description = $input['long_description'];
					$Products->additional_Information = $input['additional_Information'];
					$Products->sku = $input['sku'];
					$Products->product_code = $input['product_code'];
					$Products->weight = $input['weight'];

					$Products->height = $input['height'];
					$Products->length = $input['length'];
					$Products->width = $input['width'];


					$Products->prd_sts = $input['status'];
					// $Products->default_image = $input['default_image'];
					// $Products->zoom_image = $input['zoom_image'];


					/*$Products->gtin = $input['gtin'];*/

					$Products->product_type = $product_type;
					if (Auth::guard('vendor')->check()) {
						$Products->vendor_id = auth()->guard('vendor')->user()->id;
					} else {
						$Products->vendor_id = $sess_data[0]['vendor'];
					}




					/* save the following details */
					if ($Products->save()) {
						$prd_id = $Products->id;
						Products::updateSku($prd_id);

						Products::where('id', $prd_id)->update(array("gtin" => $prd_id));

						if ($product_type == 3) {


							ProductAttributes::where('product_id', $prd_id)->delete();
							$res = ProductAttributes::insert(array(
								'product_id' => $prd_id,
								'size_id' => 0,
								'color_id' => 0,
								'qty' => 0,
								'price' => 0,
							));
						}

						$request->session()->put('product_id', $prd_id);
						DB::table('product_reward_points')->insert(
							array(
								"product_id" => $prd_id,
								"reward_points" => 0
							)
						);

						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('edit_product', [base64_encode(1), base64_encode($prd_id)]);
						//return redirect()->route('add_product', ['level' => base64_encode(1)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}


					break;
				case 1;  // price tab
					$request->validate(
						[
							'spcl_price' => 'required',
							// 			'tax_class' => 'required'
						],
						[
							'spcl_price.required' => 'Listing price is required'
						]
					);

					if ($input['price'] != '') {
						$request->validate(
							[
								'spcl_price' => 'min:1|numeric|lt:price',

							],
							[
								'spcl_price.min' => 'Listing price should not be less than 1',
								'spcl_price.numeric' => 'Listing price should  be numeric',
								'spcl_price.lt' => 'Listing price should  be less than MRP'
							]
						);
					}

					if ($input['spcl_from_date'] != '' || $input['spcl_to_date'] != '') {
						$request->validate(
							[
								'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
								'spcl_to_date' => 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
							]
						);
					}


					$Products = new Products;
					/* save the following details */
					if ($Products->updatePrice($input, $request->session()->get('product_id'))) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('add_product', ['level' => base64_encode(2)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;



				case 2;    // categories tab
					$ProductCategories = new ProductCategories;
					if ($ProductCategories->updateCategories($input, $request->session()->get('product_id'))) {
						Products::updateSku($request->session()->get('product_id'));
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						switch ($product_type) {
							case 1:
								$level_jump = 4;
								break;

							case 2:
								$level_jump = 3;
								break;

							case 3:
								$level_jump = 3;
								break;
						}

						return redirect()->route('add_product', ['level' => base64_encode($level_jump)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;





				case 3: // attributes tab
					$product_type = $request->session()->get('product_type');

					if ($product_type == 3) {
						$color_validator = Validator::make(
							$request->all(),
							[
								'atr_color.*' => 'required',
							],
							[
								'atr_color.*.required' => "Please Selecet Color First"
							]
						);
					}

					if (array_key_exists("atr_color", $input)) {
						$color_validator = Validator::make($request->all(), [
							'atr_color.*' => 'required',
						]);


						$size_validator = Validator::make($request->all(), [
							'atr_size.*' => 'required',
						]);

						if ($color_validator->fails() && $size_validator->fails()) {
							return Redirect::back()->withErrors(['Size or color is mandatory']);
						}

						if ($color_validator->fails()) {
							$this->validate(
								$request,
								[
									'atr_size.*' => 'distinct|required',

								],
								[
									'atr_size.*.distinct' => 'Size  needs to be unique',
									'atr_size.*.required' => 'Size  is mandatory'
								]
							);
						}

						if ($size_validator->fails()) {
							$this->validate(
								$request,
								[
									'atr_color.*' => 'distinct|required',

								],
								[
									'atr_color.*.distinct' => 'Color  needs to be unique',
									'atr_color.*.required' => 'atr_color  is mandatory'
								]
							);
						}
						$this->validate(
							$request,
							[
								'atr_qty.*' => 'required',

							],
							[
								'atr_qty.*.required' => 'Quantity  is mandatory'
							]
						);
						if ($color_validator->fails() == 0 && $size_validator->fails() == 0) {
							for ($i = 0; $i < sizeof($input['atr_size']) - 1; $i++) {
								for ($k = $i; $k < sizeof($input['atr_size']) - 1; $k++) {
									if ($input['atr_size'][$i] == $input['atr_size'][$k + 1] && $input['atr_color'][$i] == $input['atr_color'][$k + 1]) {
										return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
									}
								}
							}
						}
					}
					$ProductAttributes = new ProductAttributes;
					if ($ProductAttributes->updateAttributes($input, $request->session()->get('product_id'))) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('add_product', ['level' => base64_encode(4)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;

				case 4;  // images tab
					$product_type = $request->session()->get('product_type');

					if ($product_type == 3) {

						$color_validator = Validator::make(
							$request->all(),
							[
								'color_images.*' => 'required',
							],
							[
								'color_images.*.required' => "Please Selecet Images"
							]
						);

						$configurable = array();

						if (array_key_exists("color_images", $input)) {
							foreach ($input['color_images'] as $key => $value) {
								foreach ($value as $image) {
									$destinationPath = Config::get('constants.uploads.product_images');
									$file_name = $image->getClientOriginalName();

									$img_extension = $image->getClientOriginalExtension();
									if ($img_extension == 'gif') {
										//--- uploading gif image --//
										$fn = FIleUploadingHelper::UploadGifImage($destinationPath, $image, $file_name);
									} else {

										$fn = FIleUploadingHelper::UploadImage($destinationPath, $image, $file_name, 0, 0, true);
									}


									$single = array(
										"product_id" => $request->session()->get('product_id'),
										"color_id" => $input['color_ids'][$key],
										"product_config_image" => $fn
									);
									array_push($configurable, $single);
								}
							}
						}

						$resp = DB::table('product_configuration_images')->insert($configurable);

						if ($resp) {
							MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
							return redirect()->route('add_product', ['level' => base64_encode(5)]);
						} else {
							MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
							return Redirect::back();
						}
					} else {
						$images_name = array();
						if (array_key_exists("product_images", $input)) {
							foreach ($input['product_images'] as $row) {
								array_push($images_name, $row);
							}
						}




						if ($request->hasfile('images')) {
							$request->validate([

								'images' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
							]);
							foreach ($request->file('images') as $image) {
								$destinationPath = Config::get('constants.uploads.product_images');
								$file_name = $image->getClientOriginalName();
								$fn = FIleUploadingHelper::UploadImage($destinationPath, $image, $file_name, 0, 0, true);

								array_push($images_name, $fn);
							}
						}



						if ($ProductImages->updateImages($images_name, $prd_id)) {
							MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
							return redirect()->route('add_product', ['level' => base64_encode(5)]);
						} else {
							MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
							return Redirect::back();
						}
					}


					break;


				case 5;  // manage stock tab
					$request->validate(
						[
							'manage_stock' => '',
							'qty' => 'required|not_in:0',
							'qty_out' => 'not_in:0',
							'stock_availability' => ''
						]
					);

					$Products = new Products;
					if ($Products->updateStock($input, $request->session()->get('product_id'))) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('add_product', ['level' => base64_encode(6)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}

					break;





				case 6;  // extra info tab

					dd($reqiest->all());
					$request->validate(
						[

							// 'moq' => 'required|min:1|not_in:0',
							// 			'product_brand' => 'required',
						]
					);
					$Products = new Products;
					$Products->updateFilters($input, $request->session()->get('product_id'));
					if ($Products->updateExtras($input, $request->session()->get('product_id'))) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('add_product', ['level' => base64_encode(7)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;

				case 7;   // meta info info tab
					$request->validate(
						[
							'meta_title' => 'max:60',
							'meta_description' => 'max:160',
							'meta_keyword' => 'max:255',
						]
					);
					$Products = new Products;
					if ($Products->updatemetaInfo($input, $request->session()->get('product_id'))) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						if (Auth::guard('vendor')->check()) {
							return redirect()->route('vendor_product');
						} else {
							return redirect()->route('products');
						}
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;


				case 8;  /// related product tab

					$ProductRelation = new ProductRelation;

					if ($ProductRelation->updateRelation($input, $request->session()->get('product_id'), 0)) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('add_product', ['level' => base64_encode(9)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;

				case 9; /// up sell tab

					$ProductRelation = new ProductRelation;
					if ($ProductRelation->updateRelation($input, $request->session()->get('product_id'), 1)) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return redirect()->route('add_product', ['level' => base64_encode(10)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;


				case 10; /// cross sell tab

					$ProductRelation = new ProductRelation;

					if ($ProductRelation->updateRelation($input, $request->session()->get('product_id'), 2)) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						if (Auth::guard('vendor')->check()) {
							return redirect()->route('vendor_product');
						} else {
							return redirect()->route('products');
						}
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::back();
					}
					break;
			}
		}


		switch ($product_type) {
			case 1:

				return view('admin.product.simpleProduct.form_add', [
					'page_details' => $page_details,
					'prd_id' => $request->session()->get('product_id'),
					'filters' => $filters
				]);
				break;

			case 2:
				return view('admin.product.attributeProduct.form_add', [
					'page_details' => $page_details,
					'prd_id' => $request->session()->get('product_id'),
					'filters' => $filters
				]);
				break;

			case 3:
				return view('admin.product.configurableProduct.form_add', [
					'page_details' => $page_details,
					'prd_id' => $request->session()->get('product_id'),
					'filters' => $filters
				]);
				break;
		}
	}

	public function addStock(Request $request)
	{
		$prd_id = base64_decode($request->id);
		$Product = Products::where('id', $prd_id)->first();

		$ProductAttributes = new ProductAttributes;

		$attr = $ProductAttributes->getProductAttributes($prd_id);

		if ($request->isMethod('post')) {
			$input = $request->all();
			switch ($Product->product_type) {
				case 1:
					$request->validate(
						[
							'qty' => 'required|numeric|min:1|not_in:0',
						]
					);
					if ($ProductAttributes->updateStocks($Product, $input)) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
					}
					break;



				case 3:

					if (array_key_exists("atr_color", $input)) {
						$color_validator = Validator::make($request->all(), [
							'atr_color.*' => 'required',
						]);


						$size_validator = Validator::make($request->all(), [
							'atr_size.*' => 'required',
						]);

						if ($color_validator->fails() && $size_validator->fails()) {
							return Redirect::back()->withErrors(['Size or color is mandatory']);
						}

						if ($color_validator->fails()) {
							$this->validate(
								$request,
								[
									'atr_size.*' => 'distinct|required',

								],
								[
									'atr_size.*.distinct' => 'Size  needs to be unique',
									'atr_size.*.required' => 'Size  is mandatory'
								]
							);
						}

						if ($size_validator->fails()) {
							$this->validate(
								$request,
								[
									'atr_color.*' => 'distinct|required',

								],
								[
									'atr_color.*.distinct' => 'Color  needs to be unique',
									'atr_color.*.required' => 'atr_color  is mandatory'
								]
							);
						}
						$this->validate(
							$request,
							[
								'atr_qty.*' => 'required',

							],
							[
								'atr_qty.*.required' => 'Quantity  is mandatory'
							]
						);
						if ($color_validator->fails() == 0 && $size_validator->fails() == 0) {
							for ($i = 0; $i < sizeof($input['atr_size']) - 1; $i++) {
								for ($k = $i; $k < sizeof($input['atr_size']) - 1; $k++) {
									if ($input['atr_size'][$i] == $input['atr_size'][$k + 1] && $input['atr_color'][$i] == $input['atr_color'][$k + 1]) {
										return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
									}
								}
							}
						}
					}
					$ProductAttributes = new ProductAttributes;
					if ($ProductAttributes->updateAttributes($input, $prd_id)) {
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
					}

					break;
			}

			return Redirect::back();
		}
		$page_details = array(
			"Title" => "Add Stock",
			"Method" => "1",
			"Box_Title" => "Add Stock",
			"Action_route" => route('addStock', (base64_encode($prd_id))),
			"Form_data" => array(
				"Form_field" => array(
					"product_qty_field" => array(
						'label' => 'Qty',
						'type' => 'number',
						'name' => 'qty',
						'id' => 'qty',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => $Product->qty,
						'disabled' => ''
					),
					"product_size_field" => array(
						'label' => 'Size',
						'type' => 'select',
						'name' => 'atr_size[]',
						'id' => 'atr_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($prd_id),
						'disabled' => '',
						'selected' => ''
					),
					"product_atr_sku_field" => array(
						'label' => 'Sku',
						'type' => 'text',
						'name' => 'sku[]',
						'id' => 'skuatr',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_color_field" => array(
						'label' => 'Color',
						'type' => 'select',
						'name' => 'atr_color[]',
						'id' => 'atr_color',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getColors($prd_id),
						'disabled' => '',
						'selected' => ''
					),
					"product_atr_qty_field" => array(
						'label' => 'Qty',
						'type' => 'text',
						'name' => 'atr_qty[]',
						'id' => 'atr_qty',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_barcode_field" => array(
						'label' => 'Barcode',
						'type' => 'text',
						'name' => 'barcode[]',
						'id' => 'barcode',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_price_field" => array(
						'label' => 'Price',
						'type' => 'text',
						'name' => 'atr_price[]',
						'id' => 'atr_price',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => 0,
						'disabled' => ''
					),
					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger',
						'placeholder' => '',
						'value' => 'Save'
					)
				)
			),
			"return_data" => array(
				'product_category' => array(),
				'attr' => $attr,
				'image_html' => '',
				'product_images' => array()
			)

		);



		return view('admin.product.addStock', ['Product' => $Product, 'page_details' => $page_details]);
	}
	public function edit_product(Request $request)
	{

		$id = base64_decode($request->id);

		$cats = CustomFormHelper::getProductCategory($id);
		$request->session()->put('product_id', $id);
		$products_vendor_id = Products::where('id', $id)->first();

		$products_list = Products::where('isdeleted', 0)->where('id', '!=', $id)->orderBy('id', 'DESC')->paginate(10);

		$ProductImages = new ProductImages;
		$images = $ProductImages->getImagesHtml($id);

		$ProductAttributes = new ProductAttributes;
		$filters = $ProductAttributes->getCategoryFilter($cats);
		$product_rewards_point = $ProductAttributes->rewardsPoints($id);

		$attr = $ProductAttributes->getProductAttributes($id);

		$colors = $ProductAttributes->getColor_ProductAttributes($id);


		$Products = Products::where('id', $id)->first();
		$ptype =	$Products->product_type;

		$brands = Brands::select('id', 'name')->where('isdeleted', 0)->get();
		$Materials = Materials::select('id', 'name')->where('isdeleted', 0)->get();
		$level = base64_decode($request->level);

		$qtyDisabled = '';
		if ($ptype == 3 || $ptype == 1) {
			$qtyDisabled = 'readonly';
		}
		if (Auth::guard('vendor')->check()) {
			$backroute = route('vendor_product');
		} else {
			$backroute = route('products');
		}

		$page_details = array(
			"Title" => "Edit Product",
			"Method" => "1",
			"Box_Title" => "Edit Product",
			"Action_route" => route('edit_product', [base64_encode($level), base64_encode($id)]),
			'back_route' => $backroute,
			"Form_data" => array(

				"Form_field" => array(
					"product_name_field" => array(
						'label' => 'Product Name *',
						'type' => 'text',
						'name' => 'name',
						'id' => 'name',
						'classes' => 'form-control',
						'placeholder' => 'Name',
						'value' => $Products->name,
						'disabled' => ''
					),
					"product_gtin_field" => array(
						'label' => 'Product GTIN *',
						'type' => 'text',
						'name' => 'gtin',
						'id' => 'gtin',
						'classes' => 'form-control',
						'placeholder' => 'GTIN',
						'value' => $Products->gtin,
						'disabled' => ''
					),

					"product_short_description_field" => array(
						'label' => 'Short Description *',
						'type' => 'textarea',
						'name' => 'short_description',
						'id' => 'short_description',
						'classes' => 'ckeditor form-control',
						'placeholder' => 'Short description',
						'value' => $Products->short_description,
						'disabled' => ''
					),

					"product_long_description_field" => array(
						'label' => 'Long Description *',
						'type' => 'textarea',
						'name' => 'long_description',
						'id' => 'long_description',
						'classes' => 'ckeditor form-control',
						'placeholder' => 'Long description',
						'value' => $Products->long_description,
						'disabled' => ''
					),
					"additional_Information_field" => array(
						'label' => 'Additional Information Description *',
						'type' => 'textarea',
						'name' => 'additional_Information',
						'id' => 'additional_Information',
						'classes' => 'ckeditor form-control',
						'placeholder' => 'Additional Information description',
						'value' => $Products->additional_Information,
						'disabled' => ''
					),
					"product_sku_field" => array(
						'label' => 'SKU *',
						'type' => 'text',
						'name' => 'sku',
						'id' => 'sku',
						'classes' => 'form-control',
						'placeholder' => 'sku',
						'value' => $Products->sku,
						'disabled' => ''
					),
					"product_code_field" => array(
						'label' => 'GTIN',
						'type' => 'text',
						'name' => 'product_code',
						'id' => 'product_code',
						'classes' => 'form-control',
						'placeholder' => '123',
						'value' => $Products->product_code,
						'disabled' => ''
					),
					"product_weight_field" => array(
						'label' => 'Weight (In GM)',
						'type' => 'number',
						'name' => 'weight',
						'id' => 'weight',
						'classes' => 'form-control',
						'placeholder' => 'weight',
						'value' => $Products->weight,
						'disabled' => ''
					),

					"product_height_field" => array(
						'label' => 'Height (In CM)',
						'type' => 'number',
						'name' => 'height',
						'id' => 'height',
						'classes' => 'form-control ',
						'placeholder' => 'height',
						'value' => $Products->height,
						'disabled' => ''
					),
					"product_length_field" => array(
						'label' => 'Length (In CM)',
						'type' => 'number',
						'name' => 'length',
						'id' => 'length',
						'classes' => 'form-control ',
						'placeholder' => 'Length',
						'value' => $Products->length,
						'disabled' => ''
					),
					"product_width_field" => array(
						'label' => 'Width (In CM)',
						'type' => 'number',
						'name' => 'width',
						'id' => 'width',
						'classes' => 'form-control ',
						'placeholder' => 'width',
						'value' => $Products->width,
						'disabled' => ''
					),
					"product_status_field" => array(
						'label' => 'Status *',
						'type' => 'select',
						'name' => 'status',
						'id' => 'status',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Enabled",
							), (object)array(
								"id" => "0",
								"name" => "Disabled",
							)
						),
						'disabled' => '',
						'selected' => $Products->prd_sts
					),
					"product_visibility_field" => array(
						'label' => 'Visibility',
						'type' => 'select',
						'name' => 'visibility',
						'id' => 'visibility',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Not visible individualy",
							), (object)array(
								"id" => "2",
								"name" => "Catalog",
							), (object)array(
								"id" => "3",
								"name" => "Search",
							), (object)array(
								"id" => "4",
								"name" => "Catalog,Search",
							)
						),
						'disabled' => '',
						'selected' => $Products->visibility
					),
					"product_image_field" => array(
						'label' => 'Default Image',
						'type' => 'file_special_imagepreview',
						'name' => 'default_image',
						'id' => 'file-1',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => $Products->default_image,
						'disabled' => '',
						'selected' => '',
						'onchange' => 'image_preview(event)'
					),

					"product_zoomimage_field" => array(
						'label' => 'Hover/Zoom Image',
						'type' => 'file_special_imagepreview',
						'name' => 'zoom_image',
						'id' => 'file-2',
						'classes' => 'inputfile inputfile-4',
						'placeholder' => '',
						'value' => $Products->zoom_image,
						'disabled' => '',
						'selected' => '',
						'onchange' => 'image_preview(event)'
					),
					"product_sizechart_field" => array(
						'label' => 'Product Size chart',
						'type' => 'file_special_imagepreview',
						'name' => 'size_chart',
						'id' => 'file-26',
						'classes' => 'inputfile inputfile-26',
						'placeholder' => '',
						'value' => $Products->product_size_chart,
						'disabled' => '',
						'selected' => '',
						'onchange' => 'image_preview(event)'
					),

					"product_hsn_field" => array(
						'label' => 'HSN CODE',
						'type' => 'text',
						'name' => 'hsn_code',
						'id' => 'hsn_code',
						'classes' => 'form-control',
						'placeholder' => '123',
						'value' => $Products->hsn_code,
						'disabled' => ''
					),
					"product_taxgst_field" => array(
						'label' => 'GST tax',
						'type' => 'text',
						'name' => 'tax',
						'id' => 'tax',
						'classes' => 'form-control',
						'placeholder' => ' Gst tax',
						'value' => $Products->tax,
						'disabled' => ''
					),
					"product_tax_field" => array(
						'label' => 'Tax Class *',
						'type' => 'select',
						'name' => 'tax_class',
						'id' => 'tax_class',
						'classes' => 'form-control',
						'placeholder' => 'Tax',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "None",
							), (object)array(
								"id" => "0",
								"name" => "Taxable Goods",
							)
						),
						'disabled' => '',
						'selected' => $Products->tax_class
					),
					"product_price_field" => array(
						'label' => 'MRP',
						'type' => 'number',
						'name' => 'price',
						'id' => 'price',
						'classes' => 'form-control',
						'placeholder' => 'Price',
						'value' => $Products->price,
						'disabled' => ''
					),
					"product_spcl_price_field" => array(
						'label' => 'Special Price *',
						'type' => 'number',
						'name' => 'spcl_price',
						'id' => 'spcl_price',
						'classes' => 'form-control',
						'placeholder' => 'Special Price',
						'value' => $Products->spcl_price,
						'disabled' => ''
					),

					"product_product_range_field" => array(
						'label' => "Range1",
						'type' => 'text',
						'name' => 'product_range1',
						'id' => 'product_range1',
						'classes' => 'form-control',
						'placeholder' => 'Product Range',
						'value' => $Products->product_range1,
						'disabled' => ''
					),
					"product_product_range_price_field" => array(
						'label' => "Range Price1",
						'type' => 'text',
						'name' => 'product_price1',
						'id' => 'product_price1',
						'classes' => 'form-control',
						'placeholder' => 'Product Price',
						'value' => $Products->product_price1,
						'disabled' => ''
					),


					"product_product_range_field2" => array(
						'label' => "Range2",
						'type' => 'text',
						'name' => 'product_range2',
						'id' => 'product_range2',
						'classes' => 'form-control',
						'placeholder' => 'Product Range',
						'value' => $Products->product_range2,
						'disabled' => ''
					),
					"product_product_range_price_field2" => array(
						'label' => "Range Price2",
						'type' => 'text',
						'name' => 'product_price2',
						'id' => 'product_price2',
						'classes' => 'form-control',
						'placeholder' => 'Product Price',
						'value' => $Products->product_price2,
						'disabled' => ''
					),


					"product_product_range_field3" => array(
						'label' => "Range3",
						'type' => 'text',
						'name' => 'product_range3',
						'id' => 'product_range3',
						'classes' => 'form-control',
						'placeholder' => 'Product Range',
						'value' => $Products->product_range3,
						'disabled' => ''
					),
					"product_product_range_price_field3" => array(
						'label' => "Range Price3",
						'type' => 'text',
						'name' => 'product_price3',
						'id' => 'product_price3',
						'classes' => 'form-control',
						'placeholder' => 'Product Price',
						'value' => $Products->product_price3,
						'disabled' => ''
					),
					"product_sample_price_field" => array(
						'label' => 'Sample Price ',
						'type' => 'text',
						'name' => 'sample_price',
						'id' => 'sample_price',
						'classes' => 'form-control',
						'placeholder' => 'Sample Price',
						'value' => $Products->sample_price,
						'disabled' => ''
					),

					"product_spcl_from_date_field" => array(
						'label' => 'Special Price From Date',
						'type' => 'date',
						'name' => 'spcl_from_date',
						'id' => 'spcl_from_date',
						'classes' => 'datepicker form-control',
						'placeholder' => '',
						'value' => ($Products->spcl_from_date != '') ? date("m/d/Y", strtotime($Products->spcl_from_date)) : '',
						'disabled' => ''
					),
					"product_spcl_to_date_field" => array(
						'label' => 'Special Price To Date',
						'type' => 'date',
						'name' => 'spcl_to_date',
						'id' => 'spcl_to_date',
						'classes' => 'datepicker form-control',
						'placeholder' => '',
						'value' => ($Products->spcl_to_date != '') ? date("m/d/Y", strtotime($Products->spcl_to_date)) : '',
						'disabled' => ''
					),
					"product_meta_title_field" => array(
						'label' => 'Meta Title',
						'type' => 'text',
						'name' => 'meta_title',
						'id' => 'meta_title',
						'classes' => 'form-control meta_tile',
						'placeholder' => 'Meta Title',
						'value' => $Products->meta_title,
						'disabled' => ''
					),
					"product_meta_description_field" => array(
						'label' => 'Meta Description',
						'type' => 'textarea',
						'name' => 'meta_description',
						'id' => 'meta_description',
						'classes' => 'form-control meta_description',
						'placeholder' => 'Meta description',
						'value' => $Products->meta_description,
						'disabled' => ''
					),
					"product_meta_keyword_field" => array(
						'label' => 'Meta Keyword',
						'type' => 'textarea',
						'name' => 'meta_keyword',
						'id' => 'meta_keyword',
						'classes' => 'form-control meta_keyword',
						'placeholder' => 'Meta Keyword',
						'value' => $Products->meta_keyword,
						'disabled' => ''
					),
					"product_deleivery_days_field" => array(
						'label' => "Delivery Days",
						'type' => 'number',
						'name' => 'delivery_days',
						'id' => 'delivery_days',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => $Products->delivery_days,
						'disabled' => ''
					),
					"product_return_days_field" => array(
						'label' => "Return Days",
						'type' => 'number',
						'name' => 'return_days',
						'id' => 'return_days',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => $Products->return_days,
						'disabled' => ''
					),
					"product_shipping_charge_stock_field" => array(
						'label' => "Shipping Charges",
						'type' => 'number',
						'name' => 'shipping_charge',
						'id' => 'shipping_charge',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => $Products->shipping_charges,
						'disabled' => ''
					),
					"product_rewards_point_field" => array(
						'label' => "BTA point",
						'type' => 'number',
						'name' => 'rewards_point',
						'id' => 'rewards_point',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => ($product_rewards_point) ? $product_rewards_point->reward_points : 0,
						'disabled' => ''
					),
					"product_moq_field" => array(
						'label' => "MOQ",
						'type' => 'number',
						'name' => 'moq',
						'id' => 'moq',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => ($Products->moq) ? $Products->moq : 0,
						'disabled' => ''
					),
					"product_bar_code_field" => array(
						'label' => "Product Bar Code(SPCL Char not allowed)",
						'type' => 'text',
						'name' => 'bar_code',
						'id' => 'bar_code',
						'classes' => 'form-control',
						'placeholder' => 'Bar Code',
						'value' => $Products->product_bar_code,
						'disabled' => ''
					),
					"product_manage_stock_field" => array(
						'label' => 'Manage Stock',
						'type' => 'select',
						'name' => 'manage_stock',
						'id' => 'manage_stock',
						'classes' => 'form-control',
						'placeholder' => 'manage_stock',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "NO",
							)
						),
						'disabled' => '',
						'selected' => $Products->manage_stock
					),
					"product_qty_field" => array(
						'label' => 'Qty',
						'type' => 'number',
						'name' => 'qty',
						'id' => 'qty',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => $Products->qty,
						'disabled' => $qtyDisabled
					),
					"product_qty_for_out_stock_field" => array(
						'label' => "Qty for Item to be notify",
						'type' => 'number',
						'name' => 'qty_out',
						'id' => 'qty_out',
						'classes' => 'form-control',
						'placeholder' => '0',
						'value' => $Products->qty_out,
						'disabled' => ''
					),
					"product_stock_availability_field" => array(
						'label' => 'Stock Availability',
						'type' => 'select',
						'name' => 'stock_availability',
						'id' => 'stock_availability',
						'classes' => 'form-control',
						'placeholder' => 'stock_availability',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "In Stock",
							), (object)array(
								"id" => "0",
								"name" => "Out Of Stock",
							)
						),
						'disabled' => '',
						'selected' => $Products->stock_availability
					),
					"product_material_field" => array(
						'label' => 'Material',
						'type' => 'select',
						'name' => 'material',
						'id' => 'Material',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $Materials,
						'disabled' => '',
						'selected' => $Products->material
					),

					"product_legal_country_of_manufacturing" => array(
						'label' => "Country of Manufacturing",
						'type' => 'text',
						'name' => 'country_of_manufacturing',
						'id' => 'country_of_manufacturing',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $Products->country_of_manufacturing,
						'disabled' => ''
					),

					"product_legal_manufacturer_name" => array(
						'label' => "Manufacturer's Name And Address",
						'type' => 'text',
						'name' => 'manufacturer_name',
						'id' => 'manufacturer_name',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $Products->manufacturer_name,
						'disabled' => ''
					),

					"product_legal_packer_name" => array(
						'label' => "Packer's Name And Address",
						'type' => 'text',
						'name' => 'packer_name',
						'id' => 'packer_name',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $Products->packer_name,
						'disabled' => ''
					),

					"product_legal_generic_name" => array(
						'label' => "Generic Name",
						'type' => 'text',
						'name' => 'generic_name',
						'id' => 'generic_name',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $Products->generic_name,
						'disabled' => ''
					),





					"product_related_is_shown_field" => array(
						'label' => '',
						'type' => 'select',
						'name' => 'is_related_product_shown',
						'id' => 'is_related_product_shown',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "No",
							)
						),
						'disabled' => '',
						'selected' => $Products->is_related_product_shown
					),
					"product_up_sell_is_shown_field" => array(
						'label' => '',
						'type' => 'select',
						'name' => 'is_up_sell_product_shown',
						'id' => 'is_up_sell_product_shown',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "No",
							)
						),
						'disabled' => '',
						'selected' => $Products->is_up_sell_product_shown
					),
					"product_cross_sell_is_shown_field" => array(
						'label' => '',
						'type' => 'select',
						'name' => 'is_cross_sell_product_shown',
						'id' => 'is_cross_sell_product_shown',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "Yes",
							), (object)array(
								"id" => "0",
								"name" => "No",
							)
						),
						'disabled' => '',
						'selected' => $Products->is_cross_sell_product_shown
					),
					"product_brands_field" => array(
						'label' => 'Brands',
						'type' => 'select',
						'name' => 'product_brand',
						'id' => 'product_brands_field',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => $brands,
						'disabled' => '',
						'selected' => $Products->product_brand
					),
					"product_size_field" => array(
						'label' => 'Size',
						'type' => 'select',
						'name' => 'atr_size[]',
						'id' => 'atr_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($id),
						'disabled' => '',
						'selected' => ''
					),
					"product_women_size_field" => array(
						'label' => 'Women Size',
						'type' => 'select',
						'name' => 'atr_women_size[]',
						'id' => 'atr_women_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($id),
						'disabled' => '',
						'selected' => ''
					),
					"product_men_size_field" => array(
						'label' => 'Men Size',
						'type' => 'select',
						'name' => 'atr_men_size[]',
						'id' => 'atr_men_size',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getSizes($id),
						'disabled' => '',
						'selected' => ''
					),

					"product_color_field" => array(
						'label' => 'Color',
						'type' => 'select',
						'name' => 'atr_color[]',
						'id' => 'atr_color',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => CommonHelper::getColors($id),
						'disabled' => '',
						'selected' => ''
					),
					"product_atr_qty_field" => array(
						'label' => 'Qty',
						'type' => 'text',
						'name' => 'atr_qty[]',
						'id' => 'atr_qty',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_sku_field" => array(
						'label' => 'Sku',
						'type' => 'text',
						'name' => 'atr_sku[]',
						'id' => 'atr_sku',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_barcode_field" => array(
						'label' => 'Barcode',
						'type' => 'text',
						'name' => 'barcode[]',
						'id' => 'barcode',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => '',
						'disabled' => ''
					),
					"product_atr_price_field" => array(
						'label' => 'Price',
						'type' => 'text',
						'name' => 'atr_price[]',
						'id' => 'atr_price',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => 0,
						'disabled' => ''
					),
					"product_default_price_field" => array(
						'label' => 'Default',
						'type' => 'radio',
						'name' => 'default',
						'id' => 'default',
						'classes' => 'form-control',
						'placeholder' => '',
						'value' => array(
							(object)array(
								"id" => "1",
								"name" => "",
							)
						),
						'disabled' => '',
						'selected' => ''
					),

					"submit_button_field" => array(
						'label' => '',
						'type' => 'submit',
						'name' => 'submit',
						'id' => 'submit',
						'classes' => 'btn btn-danger disableAfterCick',
						'placeholder' => '',
						'value' => 'Save'
					)
				)
			),

			"return_data" => array(
				'product_category' => $cats,
				'attr' => $attr,
				'vendor_id' => $products_vendor_id['vendor_id'],
				'color_attr' => $colors,
				'image_html' => $images,
				'product_images' => $ProductImages->getImages($id),
				'productType' => $ptype,
				'productBarCode' => $Products->product_bar_code


			)
		);

		if ($request->isMethod('post')) {
			$input = $request->all();
			switch ($level) {
				case 0;    /// general info tab

					if ($Products->isexisting == 1) {
						$request->validate(
							[
								'short_description' => 'required|max:5000',
								'long_description' => 'required|max:5000',
								'additional_Information' => 'required|max:5000',
								//    'gtin' => 'max:500|required|unique:products,gtin,'.$id.',id,isdeleted,0',
								// 'long_description' => 'required|max:60000',
								// 'sku' => 'required|max:25',
								// 'product_code' => 'required|max:25',
								'weight' => 'max:200|not_in:0',
								'height' => 'max:200|not_in:0',
								'length' => 'max:200|not_in:0',
								'width' => 'max:200|not_in:0',
								//'status' => 'required|max:25',
								'default_image' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . '',
								'zoom_image' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
							]
						);
					} else {
						$request->validate(
							[
								'name' => 'max:500|required',
								// 'name' => 'max:500|required|unique:products,name,'.$id.',id,isdeleted,0',
								//    'gtin' => 'max:500|required|unique:products,gtin,'.$id.',id,isdeleted,0',
								'short_description' => 'required|max:5000',
								'long_description' => 'required|max:5000',
								'additional_Information' => 'required|max:5000',
								// 'long_description' => 'required|max:60000',
								// 'sku' => 'required|max:25',
								// 'product_code' => 'required|max:25',
								'weight' => 'max:25',
								'height' => 'max:25',
								'length' => 'max:25',
								'width' => 'max:25',
								//'status' => 'required|max:25',
								'default_image' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . '',
								'zoom_image' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
							]
						);
					}

					if ($request->hasFile('default_image')) {
						$defualt_image = $request->file('default_image');
						$destinationPath = Config::get('constants.uploads.product_images');
						$file_name = $defualt_image->getClientOriginalName();

						$img_extension = $defualt_image->getClientOriginalExtension();
						if ($img_extension == 'gif') {
							//--- uploading gif image --//
							$file_name = FIleUploadingHelper::UploadGifImage($destinationPath, $defualt_image, $file_name);
						} else {

							$file_name = FIleUploadingHelper::UploadImage($destinationPath, $defualt_image, $file_name, 0, 0, true);
						}

						if ($file_name == '') {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
							return Redirect::back();
						} else {
							$input['default_image'] = $file_name;
						}
					}

					if ($request->hasFile('zoom_image')) {
						$zoom_image = $request->file('zoom_image');
						$destinationPath = Config::get('constants.uploads.product_images');
						$file_name = $zoom_image->getClientOriginalName();

						$img_extension = $zoom_image->getClientOriginalExtension();
						if ($img_extension == 'gif') {
							//--- uploading gif image --//
							$file_name = FIleUploadingHelper::UploadGifImage($destinationPath, $zoom_image, $file_name);
						} else {

							$file_name = FIleUploadingHelper::UploadImage($destinationPath, $zoom_image, $file_name, 0, 0, true);
						}

						if ($file_name == '') {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
							return Redirect::back();
						} else {
							$input['zoom_image'] = $file_name;
						}
					}

					$Products = new Products;

					if ($Products->updateInfo($input, $id)) {


						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(0), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(0), base64_encode($id)]);
					}


					break;
				case 1;  /// price tab
					$request->validate(
						[
							'spcl_price' => 'required',
							'tax_class' => ''
						],
						[
							'spcl_price.required' => 'Listing price is required'
						]
					);
					if ($input['price'] != '' && $input['price'] != 0) {
						$request->validate(
							[
								'spcl_price' => 'min:1|numeric|lt:price',

							],
							[
								'spcl_price.min' => 'Listing price should not be less than 1',
								'spcl_price.numeric' => 'Listing price should  be numeric',
								'spcl_price.lt' => 'Listing price should  be less than MRP'
							]
						);
					}
					if ($input['spcl_from_date'] != '' || $input['spcl_to_date'] != '') {
						$request->validate(
							[
								'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
								'spcl_to_date' => 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
							]
						);
					}


					$Products = new Products;
					/* save the following details */
					if ($Products->updatePrice($input, $id)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(1), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(1), base64_encode($id)]);
					}
					break;


				case 2;  /// categories tab
					$ProductCategories = new ProductCategories;
					if ($ProductCategories->updateCategories($input, $id)) {
						Products::updateSku($id);
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(2), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(2), base64_encode($id)]);
					}
					break;



				case 3:    /// attributes tab
					$product_type = $Products->product_type;

					if ($product_type == 3 ||  $product_type == 2) {
						$color_validator = Validator::make(
							$request->all(),
							[
								'atr_color.*' => 'required'
							],
							[
								'atr_color.*.required' => "Please Selecet Color First"
							]
						);
					}

					if (array_key_exists("atr_color", $input)) {
						$color_validator = Validator::make($request->all(), [
							'atr_color.*' => 'required',
						]);


						$size_validator = Validator::make($request->all(), [
							'atr_size.*' => 'required',
						]);

						if ($color_validator->fails() && $size_validator->fails()) {
							return Redirect::back()->withErrors(['Size or color is mandatory']);
						}

						if ($color_validator->fails()) {
							$this->validate(
								$request,
								[
									'atr_size.*' => 'distinct|required',

								],
								[
									'atr_size.*.distinct' => 'Size  needs to be unique',
									'atr_size.*.required' => 'Size  is mandatory'
								]
							);
						}

						if ($size_validator->fails()) {
							$this->validate(
								$request,
								[
									'atr_color.*' => 'distinct|required',

								],
								[
									'atr_color.*.distinct' => 'Color  needs to be unique',
									'atr_color.*.required' => 'atr_color  is mandatory'
								]
							);
						}
						$this->validate(
							$request,
							[
								'atr_qty.*' => 'required',

							],
							[
								'atr_qty.*.required' => 'Quantity  is mandatory'
							]
						);

						$this->validate(
							$request,
							[
								'atr_sku.*' => 'required',

							],
							[
								'atr_sku.*.required' => 'Sku  is mandatory'
							]
						);

						if ($product_type == 2) {
							if ($color_validator->fails() == 0 && $size_validator->fails() == 0) {
								for ($i = 0; $i < sizeof($input['atr_size']) - 1; $i++) {
									for ($k = $i; $k < sizeof($input['atr_size']) - 1; $k++) {
										if ($input['atr_size'][$i] == $input['atr_size'][$k + 1] && $input['atr_color'][$i] == $input['atr_color'][$k + 1]  && $input['unisex_type'][$i] == $input['unisex_type'][$k + 1]) {
											return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
										}
									}
								}
							}
						} else {
							if ($color_validator->fails() == 0 && $size_validator->fails() == 0) {
								for ($i = 0; $i < sizeof($input['atr_size']) - 1; $i++) {
									for ($k = $i; $k < sizeof($input['atr_size']) - 1; $k++) {
										if ($input['atr_size'][$i] == $input['atr_size'][$k + 1] && $input['atr_color'][$i] == $input['atr_color'][$k + 1]) {
											return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
										}
									}
								}
							}
						}
					}



					$ProductAttributes = new ProductAttributes;
					if ($ProductAttributes->updateAttributes($input, $id)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);

						$attrs = $ProductAttributes->getProductAttributes($id);
						if (sizeof($attrs) > 0) {
							return Redirect::route('edit_product', [base64_encode(3), base64_encode($id)]);
						} else {
							return Redirect::route('edit_product', [base64_encode(4), base64_encode($id)]);
						}
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(3), base64_encode($id)]);
					}
					break;


				case 4;  /// images tab




					if ($Products->product_type == 3 || $Products->product_type == 2) {



						$configurable = array();

						if (array_key_exists("removed_color_id", $input)) {
							DB::table('product_configuration_images')
								->whereIn('id', $input['removed_color_id'])
								->delete();
						}

						if (array_key_exists("color_images", $input)) {


							foreach ($request->file('color_images') as $key => $value) {
								foreach ($value as $image) {
									$destinationPath = Config::get('constants.uploads.product_images');
									$file_name = $image->getClientOriginalName();

									$img_extension = $image->getClientOriginalExtension();
									if ($img_extension == 'gif') {
										//--- uploading gif image --//
										$fn = FIleUploadingHelper::UploadGifImage($destinationPath, $image, $file_name);
									} else {
										$fn = FIleUploadingHelper::UploadImage($destinationPath, $image, $file_name, 0, 0, true);
									}

									$single = array(
										"product_id" => $id,
										"color_id" => $input['color_ids'][$key],
										"product_config_image" => $fn
									);
									array_push($configurable, $single);
								}
							}
						}

						$resp = DB::table('product_configuration_images')->insert($configurable);

						if ($resp) {
							Products::prdStsInactive($id);
							MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
							return Redirect::route('edit_product', [base64_encode(4), base64_encode($id)]);
						} else {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
							return Redirect::route('edit_product', [base64_encode(4), base64_encode($id)]);
						}
					} else {
						$images_name = array();
						if (array_key_exists("product_images", $input)) {
							foreach ($input['product_images'] as $row) {
								array_push($images_name, $row);
							}
						}




						if ($request->hasfile('images')) {
							$request->validate([

								'images' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
							]);

							foreach ($request->file('images') as $image) {
								$destinationPath = Config::get('constants.uploads.product_images');
								$file_name = $image->getClientOriginalName();
								$fn = FIleUploadingHelper::UploadImage($destinationPath, $image, $file_name, 0, 0, true);

								array_push($images_name, $fn);
							}
						}

						if ($ProductImages->updateImages($images_name, $id)) {
							Products::prdStsInactive($id);
							MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
							return Redirect::route('edit_product', [base64_encode(4), base64_encode($id)]);
						} else {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
							return Redirect::route('edit_product', [base64_encode(4), base64_encode($id)]);
						}
					}






					break;
					if ($input['price'] != '' && $input['price'] != 0) {
						$request->validate(
							[
								'spcl_price' => 'min:1|numeric|lt:price',

							],
							[
								'spcl_price.min' => 'Listing price should not be less than 1',
								'spcl_price.numeric' => 'Listing price should  be numeric',
								'spcl_price.lt' => 'Listing price should  be less than MRP'
							]
						);
					}

				case 5;   ///manage stock tab
					$request->validate(
						[
							'manage_stock' => '',
							'qty' => 'required|not_in:0',
							'qty_out' => 'not_in:0|lt:qty',

							'stock_availability' => ''
						],
						[
							'qty_out.lt' => 'Quantity for notify should  be less than Quantity '
						]
					);

					$Products = new Products;
					if ($Products->updateStock($input, $id)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(5), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(5), base64_encode($id)]);
					}

					break;





				case 6;  /// extra info tab


					$request->validate(
						[
							// 'moq' => 'required|numeric|min:1|not_in:0',
							// 			'product_brand' => 'required',
							'size_chart' => 'min:' . Config::get('constants.size.product_img_min') . '|max:' . Config::get('constants.size.product_img_max') . ''
						]
					);
					$extras = array(
						'hsn_code' => $input['hsn_code'],
						'product_brand' => $input['product_brand'],
						'material' => $input['material'],
						'delivery_days' => $input['delivery_days'],
						'return_days' => $input['return_days'],
						'tax' => $input['tax'],
						'product_bar_code' => !empty($input['bar_code']) ? $input['bar_code'] : '',
						// 'shipping_charges' => $input['shipping_charge'],
						'moq' => $input['moq'],
						'rewards_point' => $input['rewards_point'],
						'product_range1' => $input['product_range1'],
						'product_price1' => $input['product_price1'],
						'product_range2' => $input['product_range2'],
						'product_price2' => $input['product_price2'],
						'product_range3' => $input['product_range3'],
						'product_price3' => $input['product_price3'],
					);

					if ($request->hasFile('size_chart')) {
						$zoom_image = $request->file('size_chart');
						$destinationPath = Config::get('constants.uploads.product_size_chart');
						$file_name = $zoom_image->getClientOriginalName();

						$img_extension = $zoom_image->getClientOriginalExtension();
						if ($img_extension == 'gif') {
							//--- uploading gif image --//
							$file_name = FIleUploadingHelper::UploadGifImage($destinationPath, $zoom_image, $file_name);
						} else {

							$file_name = FIleUploadingHelper::UploadImage($destinationPath, $zoom_image, $file_name, 0, 0, true);
						}

						if ($file_name == '') {
							MsgHelper::save_session_message('danger', Config::get('messages.common_msg.image_upload_error'), $request);
							return Redirect::back();
						} else {
							$extras['product_size_chart'] = $file_name;
						}
					}

					if ($Products->product_type == 1) {
						// ProductAttributes::where('product_id',$id)->update(array("barcode"=>$input['bar_code']));

					}

					$Products = new Products;
					$Products->updateFilters($input, $id);

					/**
					 * Updating legal info 
					 * */
					// $isupate = Products::where('id',$id)->update([
					// 			"country_of_manufacturing" => $request->country_of_manufacturing,
					// 			"manufacturer_name"  => $request->manufacturer_name,
					// 			"packer_name"  => $request->packer_name,
					// 			"generic_name"  => $request->generic_name
					// ]);


					if ($Products->updateExtras($extras, $id) || $isupate) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(6), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(6), base64_encode($id)]);
					}
					break;

				case 7;   /// meta data tab
					$request->validate(
						[
							'meta_title' => 'max:60',
							'meta_description' => 'max:160',
							'meta_keyword' => 'max:255',
						]
					);
					$Products = new Products;
					if ($Products->updatemetaInfo($input, $id)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(7), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(7), base64_encode($id)]);
					}
					break;


				case 8;  /// related product tab

					$ProductRelation = new ProductRelation;


					if ($ProductRelation->updateRelation($input, $id, 0)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(8), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(8), base64_encode($id)]);
					}
					break;



				case 9; /// up sell tab

					$ProductRelation = new ProductRelation;


					if ($ProductRelation->updateRelation($input, $id, 1)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(9), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(9), base64_encode($id)]);
					}
					break;


				case 10; /// cross sell tab

					$ProductRelation = new ProductRelation;


					if ($ProductRelation->updateRelation($input, $id, 2)) {
						Products::prdStsInactive($id);
						MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
						return Redirect::route('edit_product', [base64_encode(10), base64_encode($id)]);
					} else {
						MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
						return Redirect::route('edit_product', [base64_encode(10), base64_encode($id)]);
					}
					break;
			}
		}


		switch ($Products->product_type) {
			case 1:
				return view('admin.product.simpleProduct.form_edit', [
					'page_details' => $page_details,
					'products_list' => $products_list,
					'filters' => $filters
				]);
				break;

			case 2:
				return view('admin.product.attributeProduct.form_edit', [
					'page_details' => $page_details,
					'products_list' => $products_list,
					'filters' => $filters
				]);
				break;

			case 3:
				return view('admin.product.configurableProduct.form_edit', [
					'page_details' => $page_details,
					'products_list' => $products_list,
					'filters' => $filters
				]);
				break;
		}
	}

	public function delete_prd(Request $request)
	{
		$id = base64_decode($request->id);

		$res = Products::where('id', $id)
			->update(['isdeleted' => 1]);

		DB::table('product_home_slider')->where('product_id', $id)->delete();

		if ($res) {
			MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		} else {
			MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
		}

		return Redirect::back();
	}


	public function prd_sts(Request $request)
	{
		$id = base64_decode($request->id);
		$sts = base64_decode($request->sts);

		$res = Products::where('id', $id)
			->update(['status' => ($sts == 0) ? 1 : 0]);

		if ($res) {
			MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		} else {
			MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
		}

		return Redirect::back();
	}

	public function bulk_upload_product_description_and_general(Request $request)
	{
		$inputs = $request->all();

		$request->validate(
			[
				//   'csv' => 'required|mimes:csv'
				'csv' => 'required'

			],
			[
				'csv.mimes' => 'File should be CSV',
			]
		);

		$product_type = $request->product_type;

		$file = $request->file('csv');


		$file_name = $file->getClientOriginalName();
		$res = FIleUploadingHelper::UploadDoc(Config::get('constants.uploads.csv'), $file, $file_name);

		$filepath = Config::get('constants.Url.public_url') . '/' . Config::get('constants.uploads.csv') . '/' . $res;


		$file = fopen($filepath, "r");

		$importData_arr = array();
		$i = 0;

		while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
			$num = count($filedata);

			// Skip first row (Remove below comment if you want to skip the first row)
			/*if($i == 0){
                $i++;
                continue; 
             }*/
			for ($c = 0; $c < $num; $c++) {
				$importData_arr[$i][] = $filedata[$c];
			}
			$i++;
		}
		fclose($file);


		$j = 0;


		foreach ($importData_arr as $importData) {


			if ($j > 0) {

				if ($importData[3] != "" && $importData[0] != "") {
					DB::table('product_extra_description')->insert(
						array(
							'product_id' => $importData[0],
							'product_descrip_title' => $importData[3],
							'product_descrip_content' => $importData[4],
							'product_descrip_image' => $importData[5]
						)
					);
				}
				if ($importData[1] != "" && $importData[0] != "") {
					DB::table('product_extra_general')->insert(
						array(
							'product_id' => $importData[0],
							'product_general_descrip_title' => $importData[1],
							'product_general_descrip_content' => $importData[2]
						)
					);
				}
			}
			$j++;
		}
		MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
		return Redirect::back();
	}


	public function bulk_upload_product(Request $request)
	{
		$inputs = $request->all();


		$request->validate(
			[
				//   'csv' => 'required|mimes:csv'
				'csv' => 'required'

			],
			[
				'csv.mimes' => 'File should be CSV',
			]
		);

		$product_type = $request->product_type;

		$file = $request->file('csv');


		$file_name = $file->getClientOriginalName();
		$res = FIleUploadingHelper::UploadDoc(Config::get('constants.uploads.csv'), $file, $file_name);

		$filepath = Config::get('constants.Url.public_url') . '/' . Config::get('constants.uploads.csv') . '/' . $res;


		$file = fopen($filepath, "r");

		$importData_arr = array();
		$i = 0;

		while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
			$num = count($filedata);

			// Skip first row (Remove below comment if you want to skip the first row)
			/*if($i == 0){
                $i++;
                continue; 
             }*/
			for ($c = 0; $c < $num; $c++) {
				$importData_arr[$i][] = $filedata[$c];
			}
			$i++;
		}
		fclose($file);

		$j = 0;

		$upload_error = array();
		$vdr_id = $inputs['vendor'];



		try {
			foreach ($importData_arr as $importData) {

				if ($j > 0) {

					if ($product_type == 1) {

						$res = Products::select('id')->where('isdeleted', 0)
							->where(function ($query) use ($importData) {
								$query->where('name', $importData[0]);
								// $query->orwhere('gtin', $importData[3]);

							})
							->first();


						if (!$res) {
							// $check_brand=Brands::select('id')->where('isdeleted',0)->where('id',@$importData[18])->first();


							// if($check_brand)
							// {

							$Product = new Products();


							$Product->vendor_id = $inputs['vendor'];

							$Product->name = $importData[0];
							$Product->sku = $importData[1];
							$Product->short_description = $importData[2];
							$Product->hsn_code = $importData[3];
							$Product->weight = $importData[4];
							$Product->height = $importData[5];
							$Product->length = $importData[6];
							$Product->width = $importData[7];

							$Product->prd_sts = $importData[8];
							$Product->status = $importData[8];
							// $Product->zoom_image= auth()->guard('vendor')->user()->id.$importData[10];
							//$Product->default_image= auth()->guard('vendor')->user()->id.$importData[11];

							$Product->price = $importData[9];
							$Product->spcl_price = $importData[10];

							$Product->meta_title = $importData[11];
							$Product->meta_keyword = $importData[12];
							$Product->meta_description = $importData[13];
							$Product->qty = $importData[14];
							$Product->qty_out = $importData[15];
							//$Product->product_brand= $importData[19]; //brand
							$Product->material = $importData[16];  //material


							$Product->delivery_days = $importData[17];
							$Product->return_days = $importData[18];

							$Product->long_description = $importData[21];
							$Product->additional_Information = $importData[22];


							$Product->product_type = $inputs['product_type'];
							$Product->isdeleted = 0;
							$Product->isblocked = 0;
							// $Product->status = 0;

							// 			21 pr BTA point

							if ($Product->save()) {
								$lastId = $Product->id;

								Products::where('id', $lastId)->update(array("gtin" => $lastId));

								$res = ProductAttributes::insert(array(
									'product_id' => $lastId,
									'size_id' => 0,
									'color_id' => 0,
									'qty' => $importData[14],
									'price' => 0,
								));

								$lastId = $Product->id;
								if ($importData[19] != '') {
									$rel_data['cat'] = explode("#", $importData[19]);

									$rel_new_data = array();
									foreach ($rel_data['cat'] as $row_cat) {
										$check_category = Category::select('id')->where('isdeleted', 0)->where('id', $row_cat)->first();

										if ($check_category) {
											array_push($rel_new_data, $row_cat);
										}
									}
									if (count($rel_new_data) > 0) {
										$rel_new_data['cat'] = $rel_new_data;

										$ProductCategories = new ProductCategories;
										$ProductCategories->updateCategories($rel_new_data, $lastId);
									} else {
										//echo 'Category not exist';
										array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Category Not Exist'));
									}
								}

								if ($importData[20] != '') {
									$rel_data = explode(",", $importData[20]);
									$ProductImages = new ProductImages;
									$ProductImages->updateVDRProductImages($rel_data, $lastId, $inputs['vendor']);
								}
								/* DB::table('product_reward_points')->insert(array(
									"product_id"=>$lastId,
									"reward_points"=>$importData[19],
									)); */

								// Products::updateSku($lastId);
							}
							/*} else{
							array_push($upload_error,array('row'=>$j,'error_message'=>$importData[0].' Brand or Material Not Exist'));
							continue;
					}*/
						} else {

							// 16/05/2023
							$Product = Products::find($res->id);


							$Product->vendor_id = $inputs['vendor'];

							$Product->name = $importData[0];
							$Product->sku = $importData[1];
							$Product->short_description = $importData[2];
							$Product->hsn_code = $importData[3];
							$Product->weight = $importData[4];
							$Product->height = $importData[5];
							$Product->length = $importData[6];
							$Product->width = $importData[7];

							$Product->prd_sts = $importData[8];
							$Product->status = $importData[8];
							// $Product->zoom_image= auth()->guard('vendor')->user()->id.$importData[10];
							//$Product->default_image= auth()->guard('vendor')->user()->id.$importData[11];

							$Product->price = $importData[9];
							$Product->spcl_price = $importData[10];

							$Product->meta_title = $importData[11];
							$Product->meta_keyword = $importData[12];
							$Product->meta_description = $importData[13];
							$Product->qty = $importData[14];
							$Product->qty_out = $importData[15];
							//$Product->product_brand= $importData[19]; //brand
							$Product->material = $importData[16];  //material


							$Product->delivery_days = $importData[17];
							$Product->return_days = $importData[18];

							$Product->long_description = $importData[21];
							$Product->additional_Information = $importData[22];


							$Product->product_type = $inputs['product_type'];
							$Product->isdeleted = 0;
							$Product->isblocked = 0;
							// $Product->status = 0;

							// 			21 pr BTA point

							if ($Product->save()) {
								$lastId = $Product->id;

								Products::where('id', $lastId)->update(array("gtin" => $lastId));

								$res = ProductAttributes::insert(array(
									'product_id' => $lastId,
									'size_id' => 0,
									'color_id' => 0,
									'qty' => $importData[14],
									'price' => 0,
								));

								$lastId = $Product->id;
								if ($importData[19] != '') {
									$rel_data['cat'] = explode("#", $importData[19]);

									$rel_new_data = array();
									foreach ($rel_data['cat'] as $row_cat) {
										$check_category = Category::select('id')->where('isdeleted', 0)->where('id', $row_cat)->first();

										if ($check_category) {
											array_push($rel_new_data, $row_cat);
										}
									}
									if (count($rel_new_data) > 0) {
										$rel_new_data['cat'] = $rel_new_data;

										$ProductCategories = new ProductCategories;
										$ProductCategories->updateCategories($rel_new_data, $lastId);
									} else {
										//echo 'Category not exist';
										array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Category Not Exist'));
									}
								}

								if ($importData[20] != '') {
									$rel_data = explode(",", $importData[20]);
									$ProductImages = new ProductImages;
									$ProductImages->updateVDRProductImages($rel_data, $lastId, $inputs['vendor']);
								}
								/* DB::table('product_reward_points')->insert(array(
									"product_id"=>$lastId,
									"reward_points"=>$importData[19],
									)); */

								// Products::updateSku($lastId);
							}
							// 16/05/2023

							// array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Product  exists'));
						}

						/*Simple Product*/
					} else {



						$res = Products::select('id', 'vendor_id', 'qty')->where('isdeleted', 0)
							->where(function ($query) use ($importData) {
								$query->where('name', $importData[0]);
								//$query->orwhere('gtin', $importData[3]);
							})
							->first();

						if ($res) {

							if ($res->vendor_id == $vdr_id) {
								$check_size = Sizes::select('id')->where('isdeleted', 0)->where('name', $importData[19])->first();
								$check_color = Colors::select('id')->where('isdeleted', 0)->where('name', $importData[20])->first();

								if ($check_size && $check_color) {
									// 16/05/2023
									$Product = Products::find($res->id);
									$Product->vendor_id = $inputs['vendor'];

									$Product->name = $importData[0];
									$Product->sku = $importData[1];
									$Product->short_description = $importData[2];
									$Product->hsn_code = $importData[3];
									// $Product->gtin= $importData[4]; 
									$Product->weight = $importData[4];
									$Product->height = $importData[5];
									$Product->length = $importData[6];
									$Product->width = $importData[7];
									$Product->prd_sts = $importData[8];
									$Product->status = $importData[8];
									// $Product->zoom_image= $vdr_id.$importData[10];
									// $Product->default_image= $vdr_id.$importData[11];                                       

									$Product->price = $importData[9];
									if ($importData[10] != '') {
										$Product->spcl_price = $importData[10];
									} else {
										array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Add in csv spcl price'));
										continue;
									}



									$Product->meta_title = $importData[11];
									$Product->meta_keyword = $importData[12];
									$Product->meta_description = $importData[13];
									$Product->qty = $importData[21];
									$Product->qty_out = $importData[14];
									// $Product->product_brand= $importData[19]; //brand
									$Product->material = $importData[15];  //material
									$Product->delivery_days = $importData[16];
									$Product->return_days = $importData[17];
									// $Product->shipping_charges= $importData[23];                                    
									// $Product->product_bar_code= $importData[31];

									/**
									 * Product Legal Information
									 */
									//   $Product->country_of_manufacturing= $importData[32];
									//   $Product->manufacturer_name= $importData[33];
									//   $Product->packer_name= $importData[34];
									//   $Product->generic_name= $importData[35];
									$Product->tax = $importData[24];
									$Product->long_description = $importData[25];
									$Product->additional_Information = $importData[26];


									$Product->product_type = $inputs['product_type'];
									$Product->isdeleted = 0;
									$Product->isblocked = 0;
									$Product->save();
									// 16/05/23

									$isAttributeExist = ProductAttributes::where([
										'product_id' => $res->id,
										'size_id' => $check_size['id'],
										'color_id' => $check_color['id'],
										'sku' => $importData[1]
									])->first();
									if (empty($isAttributeExist)) {
										#Insert new attributes 
										$attr = ProductAttributes::insertGetId(array(
											'product_id' => $res->id,
											'size_id' => $check_size['id'],
											'color_id' => $check_color['id'],
											'qty' => $importData[21],
											'sku' => $importData[1],
											'price' => $importData[22],
										));
										$vendor_iidd = $inputs['vendor'];
										if (auth()->guard('vendor')->check()) {
											$vendor_iidd = auth()->guard('vendor')->user()->id;
										}
									} else {
										#update existing attributes 
										ProductAttributes::where([
											'product_id' => $res->id,
											'size_id' => $check_size['id'],
											'color_id' => $check_color['id'],
											'sku' => $importData[1]
										])->update([
											'product_id' => $res->id,
											'size_id' => $check_size['id'],
											'color_id' => $check_color['id'],
											'qty' => $importData[21],
											'sku' => $importData[1],
											'price' => $importData[22],
										]);
									}

									// Products::where('id', $res->id)->update(
									// 	[
									// 		'qty' => $importData[21] + $res->qty
									// 	]
									// );

									//ProductAttributes::updateSkuAttributes($res->id,$check_color['id'],$check_size['id'],'',$attr,$vendor_iidd);
								} else {
									//echo 'Size or Color not exist';
									array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Size or Color Not Exist'));
									continue;
								}

								$lastId = $res->id;

								if ($importData[18] != '') {
									$rel_data['cat'] = explode("#", $importData[18]);

									$rel_new_data = array();
									foreach ($rel_data['cat'] as $row_cat) {
										$check_category = Category::select('id')->where('isdeleted', 0)->where('id', $row_cat)->first();

										if ($check_category) {
											array_push($rel_new_data, $row_cat);
										}
									}
									if (count($rel_new_data) > 0) {
										$rel_new_data['cat'] = $rel_new_data;

										$ProductCategories = new ProductCategories;
										$ProductCategories->updateCategories($rel_new_data, $res->id);
									} else {
										//echo 'Category not exist';
										array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Category Not Exist'));
										continue;
									}
								}

								if ($importData[23] != '') {

									$rel_data = explode(",", $importData[23]);
									foreach ($rel_data as $rel) {
										DB::table('product_configuration_images')->insert(array(
											"product_id" => $res->id,
											"color_id" => $check_color['id'],
											"product_config_image" => $rel,
										));
										//$vdr_id.
									}
								}

								// Products::updateSku($res->id);
							} else {
								array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Product  exists'));
							}
						} else {

							/*Configurable Product*/
							$check_brand = Brands::select('id')->where('isdeleted', 0)->where('id', $importData[19])->first();
							/*
									if($check_brand)
								   {
									*/
							$Product = new Products();
							$Product->vendor_id = $inputs['vendor'];

							$Product->name = $importData[0];
							$Product->sku = $importData[1];
							$Product->short_description = $importData[2];
							$Product->hsn_code = $importData[3];
							// $Product->gtin= $importData[4]; 
							$Product->weight = $importData[4];
							$Product->height = $importData[5];
							$Product->length = $importData[6];
							$Product->width = $importData[7];
							$Product->prd_sts = $importData[8];
							$Product->status = $importData[8];
							// $Product->zoom_image= $vdr_id.$importData[10];
							// $Product->default_image= $vdr_id.$importData[11];                                       

							$Product->price = $importData[9];
							if ($importData[10] != '') {
								$Product->spcl_price = $importData[10];
							} else {
								array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Add in csv spcl price'));
								continue;
							}



							$Product->meta_title = $importData[11];
							$Product->meta_keyword = $importData[12];
							$Product->meta_description = $importData[13];
							$Product->qty = $importData[21];
							$Product->qty_out = $importData[14];
							// $Product->product_brand= $importData[19]; //brand
							$Product->material = $importData[15];  //material
							$Product->delivery_days = $importData[16];
							$Product->return_days = $importData[17];
							// $Product->shipping_charges= $importData[23];                                    
							// $Product->product_bar_code= $importData[31];

							/**
							 * Product Legal Information
							 */
							//   $Product->country_of_manufacturing= $importData[32];
							//   $Product->manufacturer_name= $importData[33];
							//   $Product->packer_name= $importData[34];
							//   $Product->generic_name= $importData[35];
							$Product->tax = $importData[24];
							$Product->long_description = $importData[25];
							$Product->additional_Information = $importData[26];


							$Product->product_type = $inputs['product_type'];
							$Product->isdeleted = 0;
							$Product->isblocked = 0;
							// $Product->status= 0;
							// 			21 pr bta point

							if ($Product->save()) {
								$lastId = $Product->id;

								/*Products::where('id',$lastId)->update(array("gtin"=>$lastId));*/

								$check_size = Sizes::select('id')->where('name', $importData[19])->where('isdeleted', 0)->first();

								$check_color = Colors::select('id')->where('name', $importData[20])->where('isdeleted', 0)->first();
								if ($check_size && $check_color) {

									$attr = ProductAttributes::insertGetId(array(
										'product_id' => $lastId,
										'size_id' => $check_size['id'],
										'color_id' => $check_color['id'],
										'qty' => $importData[21],
										'sku' => $importData[1],
										'price' => $importData[22],
									));

									//dd($attr);
								} else {

									//echo 'Size or Color not exist';
									array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Size or Color Not Exist'));
									continue;
								}


								if ($importData[18] != '') {

									$rel_data['cat'] = explode("#", $importData[18]);

									$rel_new_data = array();
									foreach ($rel_data['cat'] as $row_cat) {
										$check_category = Category::select('id')->where('isdeleted', 0)->where('id', $row_cat)->first();

										if ($check_category) {
											// dd(1);
											array_push($rel_new_data, $row_cat);
										}
									}
									if (count($rel_new_data) > 0) {

										$rel_new_data['cat'] = $rel_new_data;

										$ProductCategories = new ProductCategories;

										$ProductCategories->updateCategories($rel_new_data, $lastId);

										/*ProductAttributes::updateSkuAttributes($lastId,$importData[26],$importData[25],'',$attr,auth()->guard('vendor')->user()->id);*/
									} else {

										//echo 'Category not exist';
										array_push($upload_error, array('row' => $j, 'error_message' => $importData[0] . ' Category Not Exist'));
										continue;
									}
								}

								if ($importData[23] != '') {

									$check_color = Colors::select('id')->where('name', $importData[20])->where('isdeleted', 0)->first();

									$rel_data = explode(",", $importData[23]);
									foreach ($rel_data as $rel) {
										DB::table('product_configuration_images')->insert(array(
											"product_id" => $lastId,
											"color_id" => $check_color['id'],
											"product_config_image" => $rel,
										));
									}
								}
								/*
                                        DB::table('product_reward_points')->insert(array(
                                            "product_id"=>$lastId,
                                            "reward_points"=>$importData[24],
                                            ));  */

								//Products::updateSku($lastId);
							}
							/*}				
							else{
                            
									//echo 'Brand or Material not exist';
									array_push($upload_error,array('row'=>$j,'error_message'=>$importData[0].' Brand or Material Not Exist'));
									continue;
							}*/
						}
					}
				}
				$j++;
			}


			if (count($upload_error) > 0) {
				MsgHelper::error_session_message('danger', $upload_error, $request);
			} else {
				MsgHelper::save_session_message('success', Config::get('messages.common_msg.data_save_success'), $request);
			}

			return Redirect::back();
		} catch (\Exception $e) {

			MsgHelper::save_session_message('danger', 'A problem occured in sheet', $request);
			return Redirect::back();
		}
	}
	public function uploadzipfile(Request $request)
	{

		$storepath = base_path("/uploads/products/" . $request->vendor_field);
		$foldername = base_path("/uploads/products/");
		$dir_name = $foldername . '' . $request->vendor_field;
		if (!is_dir($storepath)) {
			mkdir($dir_name);
		}
		$zip = new ZipArchive();
		$status = $zip->open($request->file('zipfile')->getRealPath());

		if ($status === true) {

			$storageDestinationPath = $storepath;
			$zip->extractTo($storageDestinationPath);
			$zip->close();
			return back()
				->with('success', 'You have successfully extracted zip.');
		} else {
			throw new \Exception($status);
		}
		exit();
	}
	public function  getfoldername(Request $request)
	{

		$data = [];
		$i = 0;
		$dir = base_path() . '/uploads/products/' . $request->vendorid;
		$publicpath = public_path();
		$dirList = scandir($dir);
		foreach ($dirList as $key => $value) {
			$rountname = route('viewspageimages', [$request->vendorid, $value]);
			if (strpos($value, '.') !== false) {
			} else {

				echo '<div class="col-6 col-xs-6 col-sm-3 col-md-2">
			<div class="folder-img">
			<button type="button" class="close" data-dismiss="alert" data-id="' . $value . '">&times;</button>
				<a href="' . $rountname . '">
			<img data-id="' . $value . '" class="viewsimages" src="' . asset('public/images/folderpic.png') . '"/>
					
			<h6>' . $value . '</h6>
					</a>
			</div>
			</div>';
			}
		}
		exit();
	}
	public function  serachfoldername(Request $request)
	{

		$data = [];
		$i = 0;
		$dir = base_path() . '/uploads/products/' . $request->vendorid;
		$publicpath = public_path();
		$dirList = scandir($dir);
		foreach ($dirList as $key => $value) {
			if ($request->serachdata == $value) {
				$rountname = route('viewspageimages', [$request->vendorid, $value]);
				if (strpos($value, '.') !== false) {
				} else {

					echo '<div class="col-6 col-xs-6 col-sm-3 col-md-2">
					<div class="folder-img">
					<button type="button" class="close" data-dismiss="alert" data-id="' . $value . '">&times;</button>
						<a href="' . $rountname . '">
					<img data-id="' . $value . '" class="viewsimages" src="' . asset('public/images/folderpic.png') . '"/>
							
					<h6>' . $value . '</h6>
							</a>
					</div>
					</div>';
				}
			}
		}
		exit();
	}
	public function deletefolder(Request $request)
	{
		$data = [];
		$folderPath = base_path() . '/uploads/products/' . $request->vendorid . '/' . $request->foldername;
		$dd = File::deleteDirectory($folderPath);
		echo "delete done";
		exit();
	}
	public function viewspageimages(Request $request, $userid, $folderid)
	{

		$dir = base_path() . '/uploads/products/' . $userid . '/' . $folderid;
		$dirList = scandir($dir);
		$page_details = array(
			"Title" => "Product Images",
			"Method" => "1",
			"Box_Title" => "Product Images",
			"Action_route" => "",
			"Form_data" => array()
		);

		return view('admin.product.product_image_view', [
			'page_details' => $page_details, 'productimages' => $dirList, 'userid' => $userid, 'folderid' => $folderid
		]);
		exit();
	}
	public function multideleteimages(Request $request)
	{
		$folderPath = base_path() . '/uploads/products/' . $request->userid . '/' . $request->folderid;
		foreach ($request->product_id as $key => $value) {

			$alldelete = $folderPath . '/' . $value;
			File::delete($alldelete);
		}
		return redirect()->route('viewspageimages', [$request->userid, $request->folderid])->with('success', 'Selected iamges delete successfully.');;
		exit();
	}
}
