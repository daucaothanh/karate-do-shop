@extends('layouts.quan_tri')

@section('title', 'Chỉnh sửa Sản phẩm Võ thuật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-pen-to-square text-warning mr-2"></i> CHỈNH SỬA SẢN PHẨM: <small class="text-dark">{{ $product->name }}</small></h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <div class="x_title">
        <h2>Cập nhật thông tin chi tiết</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column: Basic Info -->
                <div class="col-md-8 col-sm-12">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tên sản phẩm võ thuật <span class="text-danger">*</span>:</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Danh mục <span class="text-danger">*</span>:</label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Đơn vị tính <span class="text-danger">*</span>:</label>
                            <input type="text" name="unit" list="unit-list" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit) }}" required>
                            <datalist id="unit-list">
                                @foreach($units as $unit)
                                    <option value="{{ $unit }}"></option>
                                @endforeach
                            </datalist>
                            @error('unit') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Giá bán (VNĐ) <span class="text-danger">*</span>:</label>
                            <div class="input-group">
                                <input type="number" name="price" step="1000" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', (int)$product->price) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">₫</span>
                                </div>
                            </div>
                            @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Số lượng tồn kho <span class="text-danger">*</span>:</label>
                            <input type="number" name="stock" id="totalStockInput" min="0" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" readonly required>
                            @error('stock') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @php $sizeStocks = $product->size_stocks ?? []; @endphp
                    <div class="card p-3 mb-3 border border-danger" style="background: #fffafa; border-radius: 8px;">
                        <label class="font-weight-bold text-danger mb-2"><i class="fa-solid fa-ruler-combined mr-1"></i> Tồn kho theo size S / M / L / XL</label>
                        <div class="row">
                            @foreach(['S', 'M', 'L', 'XL'] as $size)
                                <div class="col-6 col-md-3 form-group mb-2">
                                    <label class="small font-weight-bold">Size {{ $size }}</label>
                                    <input type="number" name="size_stocks[{{ $size }}]" class="form-control size-stock-input" min="0" value="{{ old('size_stocks.' . $size, $sizeStocks[$size] ?? '') }}">
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Tổng tồn kho sẽ tự động bằng tổng số lượng của bốn size trên.</small>
                    </div>

                    <!-- Colors & Sizes Attributes Card -->
                    <div class="card p-3 mb-3 border" style="background: #fdfdfd; border-radius: 8px;">
                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="fa-solid fa-palette mr-1"></i> Phân loại Màu sắc & Kích thước (Tùy chọn mua hàng)
                        </h6>

                        <!-- Colors Input -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">
                                <i class="fa-solid fa-brush mr-1 text-primary"></i> Màu sắc sản phẩm (Phân cách bằng dấu phẩy):
                            </label>
                            <input type="text" id="product_colors_input" name="colors" class="form-control font-weight-bold" value="{{ old('colors', $product->colors) }}" placeholder="Ví dụ: Đỏ, Xanh dương, Trắng, Đen">
                            <div class="mt-2">
                                <small class="text-muted mr-2">Gợi ý nhanh:</small>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 mr-1" onclick="addColorTag('Đỏ')">🔴 Đỏ</button>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 mr-1" onclick="addColorTag('Xanh dương')">🔵 Xanh dương</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 mr-1" onclick="addColorTag('Trắng')">⚪ Trắng</button>
                                <button type="button" class="btn btn-sm btn-outline-dark py-0 px-2 mr-1" onclick="addColorTag('Đen')">⚫ Đen</button>
                                <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2 mr-1" onclick="addColorTag('Vàng')">🟡 Vàng</button>
                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 mr-1" onclick="addColorTag('Cam')">🟠 Cam</button>
                                <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 mr-1" onclick="addColorTag('Xanh lá')">🟢 Xanh lá</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 mr-1" onclick="addColorTag('Nâu')">🟤 Nâu</button>
                                <button type="button" class="btn btn-sm btn-light py-0 px-2 text-danger" onclick="document.getElementById('product_colors_input').value = ''">Xóa</button>
                            </div>
                        </div>

                        <!-- Sizes Input -->
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">
                                <i class="fa-solid fa-ruler-combined mr-1 text-success"></i> Kích cỡ / Size (Phân cách bằng dấu phẩy):
                            </label>
                            <input type="text" id="product_sizes_input" name="sizes" class="form-control font-weight-bold" value="{{ old('sizes', $product->sizes) }}" placeholder="Ví dụ: S, M, L, XL hoặc Số 3, Số 4, Số 5 hoặc 240cm, 260cm">
                            <div class="mt-2">
                                <small class="text-muted mr-2">Gợi ý nhanh:</small>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 mr-1" onclick="setSizesPreset('S, M, L, XL')">Găng/Giáp (S, M, L, XL)</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 mr-1" onclick="setSizesPreset('Số 3, Số 4, Số 5, Số 6, Số 7')">Võ phục (Số 3 - Số 7)</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 mr-1" onclick="setSizesPreset('240cm, 260cm, 280cm, 300cm')">Đai võ (240cm - 300cm)</button>
                                <button type="button" class="btn btn-sm btn-light py-0 px-2 text-danger" onclick="document.getElementById('product_sizes_input').value = ''">Xóa</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Mô tả chi tiết sản phẩm:</label>
                        <textarea name="description" class="form-control" rows="6">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Right Column: Status & Images -->
                <div class="col-md-4 col-sm-12">
                    <div class="card p-3 bg-light border mb-3">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-toggle-on text-primary mr-1"></i> Trạng thái bán hàng</h6>
                        <div class="form-group mb-0">
                            <select name="status" class="form-control font-weight-bold">
                                <option value="in_stock" {{ old('status', $product->status) == 'in_stock' ? 'selected' : '' }}>✅ Còn hàng trong kho</option>
                                <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>⚠️ Tạm hết hàng</option>
                                <option value="discontinued" {{ old('status', $product->status) == 'discontinued' ? 'selected' : '' }}>🚫 Ngừng kinh doanh</option>
                            </select>
                        </div>
                    </div>

                    <!-- Existing Images -->
                    <div class="card p-3 bg-light border mb-3">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-images text-danger mr-1"></i> Hình ảnh hiện tại ({{ $product->images->count() }})</h6>
                        @if($product->images->count() > 0)
                            <div class="d-flex flex-wrap">
                                @foreach($product->images as $img)
                                    <div class="position-relative mr-2 mb-2" style="width: 80px; height: 80px;">
                                        <img src="{{ asset($img->image) }}" class="img-thumbnail w-100 h-100" style="object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute p-0 d-flex align-items-center justify-content-center" style="top: -5px; right: -5px; width: 22px; height: 22px; border-radius: 50%;" onclick="if(confirm('Xóa ảnh này?')) document.getElementById('delete-img-{{ $img->id }}').submit();">
                                            <i class="fa-solid fa-xmark" style="font-size: 11px;"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="small text-muted mb-0">Chưa có hình ảnh nào được tải lên.</p>
                        @endif
                    </div>

                    <!-- Add More Images -->
                    <div class="card p-3 bg-light border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-cloud-arrow-up text-info mr-1"></i> Tải thêm hình ảnh mới</h6>
                        <div class="custom-file mb-3">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="editProductImagesInput" onchange="previewImages(this, 'edit-images-preview-container')">
                            <label class="custom-file-label" for="editProductImagesInput">Chọn ảnh...</label>
                        </div>
                        <div id="edit-images-preview-container" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-right">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light border mr-2">Hủy bỏ</a>
                <button type="submit" class="btn btn-karate px-4 font-weight-bold">
                    <i class="fa-solid fa-save mr-1"></i> CẬP NHẬT SẢN PHẨM
                </button>
            </div>
        </form>

        <!-- Hidden Forms to delete single images -->
        @foreach($product->images as $img)
            <form id="delete-img-{{ $img->id }}" action="{{ route('admin.products.images.destroy', $img->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function addColorTag(color) {
    let input = document.getElementById('product_colors_input');
    let current = input.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
    if (!current.includes(color)) {
        current.push(color);
        input.value = current.join(', ');
    }
}
function setSizesPreset(sizesStr) {
    document.getElementById('product_sizes_input').value = sizesStr;
}
function previewImages(input, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail mr-2 mb-2';
                img.style.width = '70px';
                img.style.height = '70px';
                img.style.objectFit = 'cover';
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var total = document.getElementById('totalStockInput');
        var inputs = document.querySelectorAll('.size-stock-input');
        function updateTotal() {
            var hasSizeStock = Array.from(inputs).some(function (input) { return input.value !== ''; });
            if (!hasSizeStock) return;
            var sum = 0;
            inputs.forEach(function (input) { sum += parseInt(input.value, 10) || 0; });
            total.value = sum;
        }
        inputs.forEach(function (input) { input.addEventListener('input', updateTotal); });
        updateTotal();
    });
</script>
@endpush
