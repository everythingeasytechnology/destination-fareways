<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    /**
     * Display a listing of the offers.
     */
    public function index()
    {
        $offers = Offer::where('status', '!=', 'draft')->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create()
    {
        return view('admin.offers.create');
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request)
    {
        $draftId = $request->input('draft_id') ?: null;

        // Slug must be unique only among non-draft offers; also ignore the current draft itself.
        $slugRule = [
            'nullable', 'string', 'max:255',
            Rule::unique('offers', 'slug')
                ->where(fn ($q) => $q->where('status', '!=', 'draft'))
                ->ignore($draftId),
        ];

        $rules = [
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => $slugRule,
            'subtitle'       => ['nullable', 'string', 'max:255'],
            'short_desc'     => ['nullable', 'string', 'max:500'],
            'description'    => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'banner_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'from_city'      => ['required', 'string', 'max:100'],
            'to_city'        => ['required', 'string', 'max:100'],
            'airline'        => ['nullable', 'string', 'max:100'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'offer_price'    => ['required', 'numeric', 'min:0'],
            'discount_label' => ['nullable', 'string', 'max:100'],
            'promo_code'     => ['nullable', 'string', 'max:50'],
            'valid_from'     => ['nullable', 'date'],
            'valid_until'    => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_featured'    => ['required', 'boolean'],
            'status'         => ['required', 'string', 'in:active,inactive'],
            'sort_order'     => ['required', 'integer', 'min:0'],
            'seo_title'      => ['nullable', 'string', 'max:255'],
            'seo_description'=> ['nullable', 'string', 'max:500'],
            'seo_keywords'   => ['nullable', 'string', 'max:255'],
            'og_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];

        $validatedData = $request->validate($rules);

        // Generate / normalise slug
        $validatedData['slug'] = $validatedData['slug']
            ? Str::slug($validatedData['slug'])
            : Str::slug($validatedData['title']);

        // Convert & store images as WebP
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->storeAsWebP($request->file('image'), 'uploads/offers');
        }
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $this->storeAsWebP($request->file('banner_image'), 'uploads/offers');
        }
        if ($request->hasFile('og_image')) {
            $validatedData['og_image'] = $this->storeAsWebP($request->file('og_image'), 'uploads/offers');
        }

        $validatedData['created_by'] = Auth::id() ?? 1;

        // If an auto-saved draft exists, update it instead of creating a duplicate
        if ($draftId) {
            $draft = Offer::where('id', $draftId)->where('status', 'draft')->first();
            if ($draft) {
                $draft->update($validatedData);
                return redirect()->route('admin.offers.index')->with('success', 'Flight offer published successfully.');
            }
        }

        // Ensure slug uniqueness when creating fresh
        $base  = $validatedData['slug'];
        $count = Offer::where('slug', $base)->count();
        if ($count > 0) {
            $validatedData['slug'] = $base . '-' . time();
        }

        Offer::create($validatedData);

        return redirect()->route('admin.offers.index')->with('success', 'Flight offer created successfully.');
    }

    /**
     * Auto-save a draft offer via AJAX.
     */
    public function autosave(Request $request)
    {
        $draftId = $request->input('draft_id');

        $data = $request->only([
            'title', 'subtitle', 'slug', 'short_desc', 'description',
            'from_city', 'to_city', 'airline', 'discount_label', 'promo_code',
            'is_featured', 'sort_order', 'seo_title', 'seo_description', 'seo_keywords',
        ]);

        if ($request->filled('original_price')) {
            $data['original_price'] = $request->input('original_price');
        }
        if ($request->filled('offer_price')) {
            $data['offer_price'] = $request->input('offer_price');
        }
        if ($request->filled('valid_from')) {
            $data['valid_from'] = $request->input('valid_from');
        }
        if ($request->filled('valid_until')) {
            $data['valid_until'] = $request->input('valid_until');
        }

        $data['status'] = 'draft';
        $data['created_by'] = Auth::id() ?? 1;
        $data['is_featured'] = $data['is_featured'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($draftId) {
            $offer = Offer::where('id', $draftId)->where('status', 'draft')->first();
            if ($offer) {
                $offer->update($data);
                return response()->json(['success' => true, 'id' => $offer->id]);
            }
        }

        if (empty($data['title'])) {
            return response()->json(['success' => false, 'message' => 'Enter a title first to enable auto-save.']);
        }

        $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $base = $slug;
        $i = 1;
        while (Offer::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug'] = $slug;
        $data['original_price'] = $data['original_price'] ?? 0;
        $data['offer_price'] = $data['offer_price'] ?? 0;

        $offer = Offer::create($data);

        return response()->json(['success' => true, 'id' => $offer->id]);
    }

    /**
     * Show the form for editing the specified offer.
     */
    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:offers,slug,' . $offer->id, 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'from_city' => ['required', 'string', 'max:100'],
            'to_city' => ['required', 'string', 'max:100'],
            'airline' => ['nullable', 'string', 'max:100'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'offer_price' => ['required', 'numeric', 'min:0'],
            'discount_label' => ['nullable', 'string', 'max:100'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:valid_from'],
            'is_featured' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive,draft'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        // Convert & store images as WebP (delete old file first)
        if ($request->hasFile('image')) {
            if ($offer->image) Storage::disk('public')->delete($offer->image);
            $validatedData['image'] = $this->storeAsWebP($request->file('image'), 'uploads/offers');
        }
        if ($request->hasFile('banner_image')) {
            if ($offer->banner_image) Storage::disk('public')->delete($offer->banner_image);
            $validatedData['banner_image'] = $this->storeAsWebP($request->file('banner_image'), 'uploads/offers');
        }
        if ($request->hasFile('og_image')) {
            if ($offer->og_image) Storage::disk('public')->delete($offer->og_image);
            $validatedData['og_image'] = $this->storeAsWebP($request->file('og_image'), 'uploads/offers');
        }

        $offer->update($validatedData);

        return redirect()->route('admin.offers.index')->with('success', 'Flight offer updated successfully.');
    }

    /**
     * Convert an uploaded image to WebP using PHP GD and store it.
     * Falls back to storing the original file if GD / WebP support is unavailable.
     */
    private function storeAsWebP(\Illuminate\Http\UploadedFile $file, string $directory, int $quality = 82): string
    {
        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            return $file->store($directory, 'public');
        }

        $source = $file->getRealPath();
        $mime   = $file->getMimeType();

        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($source),
            'image/png'  => @imagecreatefrompng($source),
            'image/webp' => @imagecreatefromwebp($source),
            'image/gif'  => @imagecreatefromgif($source),
            default      => null,
        };

        if (!$image) {
            return $file->store($directory, 'public');
        }

        // For PNG: convert palette to true-colour and preserve alpha channel
        if ($mime === 'image/png') {
            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $temp    = tempnam(sys_get_temp_dir(), 'webp_');
        $relPath = $directory . '/' . Str::uuid() . '.webp';

        imagewebp($image, $temp, $quality);
        imagedestroy($image);

        Storage::disk('public')->put($relPath, file_get_contents($temp));
        @unlink($temp);

        return $relPath;
    }

    /**
     * Remove the specified offer from storage (soft delete).
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Flight offer moved to trash.');
    }
}
