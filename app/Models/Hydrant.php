<?php

namespace App\Models;

use App\Models\User;
use App\Models\Zone;
use App\Models\Brand;
use App\Models\Floor;
use App\Models\Building;
use App\Models\HydrantType;
use App\Models\ExtinguisherCondition;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hydrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_hydrant', 
        'location', 
        'description', 
        'qr_code', 
        'is_active', 
        'user_id', 
        'updated_by', 
        'zone_id', 
        'building_id', 
        'floor_id', 
        'brand_id', 
        'hydrant_type_id', 
        'extinguisher_condition_id'
    ];

    protected $casts = [
        'user_id' => 'integer', 
        'zone_id' => 'integer', 
        'building_id' => 'integer', 
        'floor_id' => 'integer', 
        'brand_id' => 'integer', 
        'hydrant_type_id' => 'integer', 
        'extinguisher_condition_id' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // accessors untuk format tampilan
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
    }

    public function getExtinguisherConditionBadgeAttribute()
    {
        switch ($this->extinguisherCondition->slug) {
            case 'normal':
                return '<span class="badge bg-success">Baik</span>';
            case 'perlu-servis':
                return '<span class="badge bg-warning text-dark">Perlu Servis</span>';
            case 'rusak':
                return '<span class="badge bg-danger">Rusak</span>';
            default:
                return '<span class="badge bg-secondary">Tidak Diketahui</span>';
        }
    }

    // relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function udpatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function hydrantType()
    {
        return $this->belongsTo(HydrantType::class);
    }

    public function extinguisherCondition()
    {
        return $this->belongsTo(ExtinguisherCondition::class);
    }

    // mutator
    /**
     * mutator untuk number hydrant selalu UPPERCASE & insert number hydrant as qr code
     */
    public function setNumberHydrantAttribute($value)
    {
        $this->attributes['number_hydrant'] = strtoupper($value);

        // Generate QR code otomatis dari number_hydrant
        if (empty($this->qr_code)) {
            $this->attributes['qr_code'] = 'ARFF-' . strtoupper($value);
        }
    }

    // =================== methode buat qrcode Hydrant
    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($hydrant) {
            // Generate qrcode otomatis jika belum ada
            if (empty($hydrant->qr_code)) {
                $hydrant->qr_code = $hydrant->generateQrCode();
            }
        });
    }

    /**
     * Generate QR Code dari data Hydrant
     */
    public function generateQrCode()
    {
        // Data yang akan diencode ke QR Code
        return 'ARFF-' . $this->number_hydrant;
    }

    /**
     * Accessor untuk QR Code image (SVG)
     */
    public function getQrCodeSvgAttribute()
    {
        if ($this->qr_code) {
            return QrCode::size(150)
                ->color(40, 40, 40)
                ->backgroundColor(255, 255, 255)
                ->generate($this->getQrCodeContent());
        }
        return null;
    }

    /**
     * Accessor untuk QR Code image kecil (SVG)
     */
    public function getQrCodeSmallAttribute()
    {
        if ($this->qr_code) {
            return QrCode::size(80) // Ukuran lebih kecil
                ->color(40, 40, 40)
                ->backgroundColor(255, 255, 255)
                ->generate($this->getQrCodeContent());
        }
        return null;
    }

    /**
     * Accessor untuk QR Code image (PNG - base64)
     */
    public function getQrCodePngAttribute()
    {
        if ($this->qr_code) {
            return QrCode::size(200)
                ->format('png')
                ->color(0, 0, 0)
                ->backgroundColor(255, 255, 255)
                ->generate($this->getQrCodeContent());
        }
        return null;
    }

    /**
     * Content yang akan diencode ke QR Code
     */
    public function getQrCodeContent()
    {
        // mereturn nomor hydrant dari table ke hasil scan
        return $this->qr_code;
    }

    /**
     * Cari Hydrant berdasarkan QR code
     */
    public function scopeByQrCode($query, $qrCode)
    {
        return $query->where('qr_code', $qrCode);
    }

    /**
     * Download QR Code sebagai PNG
     */
    public function downloadQrCode(Hydrant $hydrant)
    {
        if (!$hydrant->qr_code) {
            return redirect()->back()->with('error', 'QR Code tidak tersedia');
        }

        $qrCode = QrCode::size(400)
            ->format('png')
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($hydrant->getQrCodeContent());
        
        $filename = 'qrcode-hydrant-' . $hydrant->number_hydrant . '.png';
        
        return response($qrCode)
            ->header('Content-type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Download QR Code sebagai SVG
     */
    public function downloadQrCodeSvg(Hydrant $hydrant)
    {
        if (!$hydrant->qr_code) {
            return redirect()->back()->with('error', 'QR Code tidak tersedia');
        }

        $qrCode = QrCode::size(400)
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($hydrant->getQrCodeContent());
        
        $filename = 'qrcode-hydrant-' . $hydrant->number_hydrant . '.svg';
        
        return response($qrCode)
            ->header('Content-type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    // =================================== batas qr code
}
