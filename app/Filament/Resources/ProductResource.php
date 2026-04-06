<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Sản phẩm';

    protected static ?string $modelLabel = 'Sản phẩm';

    // Slug bắt đầu bằng MSSV theo yêu cầu
    protected static ?string $slug = '23810310109-products';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Grid 2 cột chính
            Forms\Components\Grid::make(2)->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Tên sản phẩm')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                        $set('slug', Str::slug($state))
                    ),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('category_id')
                    ->label('Danh mục')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft'        => 'Bản nháp',
                        'published'    => 'Đã đăng',
                        'out_of_stock' => 'Hết hàng',
                    ])
                    ->required()
                    ->default('draft'),

                Forms\Components\TextInput::make('price')
                    ->label('Giá (VNĐ)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix('₫')
                    ->rule('min:0'),

                Forms\Components\TextInput::make('stock_quantity')
                    ->label('Số lượng tồn kho')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(0),

                // Trường sáng tạo: discount_percent
                Forms\Components\TextInput::make('discount_percent')
                    ->label('Giảm giá (%)')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0)
                    ->suffix('%')
                    ->helperText('Nhập 0-100. Giá sau giảm sẽ được tính tự động.'),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Ảnh đại diện')
                    ->image()
                    ->directory('products')
                    ->maxFiles(1)
                    ->columnSpanFull(),
            ]),

            // Rich Editor mô tả - full width
            Forms\Components\RichEditor::make('description')
                ->label('Mô tả sản phẩm')
                ->columnSpanFull()
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike',
                    'bulletList', 'orderedList', 'blockquote',
                    'h2', 'h3', 'link', 'undo', 'redo',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Ảnh')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' ₫')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Giảm giá')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Tồn kho')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'gray'    => 'draft',
                        'success' => 'published',
                        'danger'  => 'out_of_stock',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft'        => 'Bản nháp',
                        'published'    => 'Đã đăng',
                        'out_of_stock' => 'Hết hàng',
                        default        => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Lọc theo danh mục')
                    ->options(Category::all()->pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft'        => 'Bản nháp',
                        'published'    => 'Đã đăng',
                        'out_of_stock' => 'Hết hàng',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
