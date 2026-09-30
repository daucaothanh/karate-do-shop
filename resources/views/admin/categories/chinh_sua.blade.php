@extends('layouts.quan_tri')

@section('title', 'Chỉnh sửa Danh mục Võ thuật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-pen-to-square text-warning mr-2"></i> CHỈNH SỬA DANH MỤC: <small class="text-dark">{{ $category->name }}</small></h3>
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
        <h2>Cập nhật thông tin danh mục</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tên danh mục <span class="text-danger">*</span>:</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Mô tả danh mục:</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="card p-3 bg-light border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-image text-danger mr-1"></i> Hình ảnh danh mục</h6>
                        @if($category->image)
                            <div class="mb-3">
                                <img src="{{ asset($category->image) }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif

                        <div class="custom-file mb-3">
                            <input type="file" name="image" class="custom-file-input" id="catEditImageInput" onchange="previewImages(this, 'cat-edit-image-preview')">
                            <label class="custom-file-label" for="catEditImageInput">Đổi ảnh mới...</label>
                        </div>
                        <div id="cat-edit-image-preview" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-right">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light border mr-2">Hủy bỏ</a>
                <button type="submit" class="btn btn-karate px-4 font-weight-bold">
                    <i class="fa-solid fa-save mr-1"></i> CẬP NHẬT DANH MỤC
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
