@extends('layouts.quan_tri')

@section('title', 'Thêm mới Danh mục Võ thuật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-plus-circle text-danger mr-2"></i> THÊM MỚI DANH MỤC VÕ THUẬT</h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <div class="x_title">
        <h2>Thông tin danh mục mới</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tên danh mục <span class="text-danger">*</span>:</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Ví dụ: Võ phục Karate, Đai võ thuật, Giáp & Găng bảo hộ, Binh khí tập luyện..." value="{{ old('name') }}" required autofocus>
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Mô tả danh mục:</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Mô tả về các dòng sản phẩm thuộc danh mục này...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="card p-3 bg-light border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-image text-danger mr-1"></i> Hình ảnh / Banner danh mục</h6>
                        <p class="small text-muted mb-2">Ảnh đại diện hoặc biểu trưng cho danh mục.</p>

                        <div class="custom-file mb-3">
                            <input type="file" name="image" class="custom-file-input" id="catImageInput" onchange="previewImages(this, 'cat-image-preview')">
                            <label class="custom-file-label" for="catImageInput">Chọn ảnh...</label>
                        </div>
                        <div id="cat-image-preview" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-right">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light border mr-2">Hủy bỏ</a>
                <button type="submit" class="btn btn-karate px-4 font-weight-bold">
                    <i class="fa-solid fa-save mr-1"></i> TẠO DANH MỤC
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
