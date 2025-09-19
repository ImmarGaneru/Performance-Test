export interface Baseline {
    reff_id: number; // reff_id from database
    description: string;
    keterangan: string;
    unit_id: number;
    date_created: string;
    is_default: number; // 0 or 1
    perf_id: number | null; // The performance it was created from
    details?: BaselineDetail[]; // Array of baseline details
}

export interface BaselineDetail {
    id: number;
    output_id: string;
    value: number;
    output_tag: {
        description: string;
        satuan: string;
    }
}

export interface Performance {
    id: number; // perf_id from database
    description: string;
    date_perfomance: string;
    date_created: string;
    status: 'Editable' | 'Locked';
    unit_id: number;
    unit_name?: string; // Optional for backward compatibility
    type?: string; // Type field
    weight?: string; // Weight field
    reference_exists?: boolean; // New field to indicate if references exist
}
