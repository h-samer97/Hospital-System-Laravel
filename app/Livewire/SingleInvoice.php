<?php

namespace App\Livewire;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Section;
use App\Models\SingleInvioce;
use App\Models\SingleServices;
use Exception;
use Livewire\Component;

class SingleInvoice extends Component
{
    public $invoiceSave;

    public $invoiceUpdate;

    public $show_table = true;

    public $tax_rate = 17;

    public $price;

    public $discount_value;

    public $patient_id;

    public $doctor_id;

    public $section_id;

    public $type;

    public $Service_id;

    public function render()
    {
        return view('livewire.SingleInvoices.table', [
            'single_invoices' => SingleInvioce::with(['service', 'patient', 'doctor', 'section'])->get(),
            'patients' => Patient::all(),
            'doctors' => Doctor::all(),
            'sections' => Section::all(),
            'services' => SingleServices::all(),
            'subtotal' => $Total_after_discount = ((is_numeric($this->price) ? $this->price : 0)) - ((is_numeric($this->discount_value) ? $this->discount_value : 0)),
            'tax_value' => $Total_after_discount * ((is_numeric($this->tax_rate) ? $this->tax_rate : 0) / 100),
        ]);
    }

    public function show_form_add()
    {
        $this->show_table = false;
    }

    public function get_section()
    {

        $doctor = Doctor::with(['section'])->where('id', $this->doctor_id)->first();
        $this->section_id = $doctor->section->id;

    }

    public function get_price()
    {
        $this->price = SingleServices::where('id', $this->Service_id)->first()->price;
    }

    public function store()
    {

        try {

            $single_invoices = new SingleInvioce;
            $single_invoices->invoice_date = now();
            $single_invoices->patient_id = $this->patient_id;
            $single_invoices->doctor_id = $this->doctor_id;
            $single_invoices->section_id = $this->section_id;
            $single_invoices->service_id = $this->Service_id;
            $single_invoices->price = $this->price;
            $single_invoices->discount_value = $this->discount_value;
            $single_invoices->tax_rate = $this->tax_rate;
            $discounted_price = $this->price - $this->discount_value;
            $single_invoices->tax_value = $discounted_price * ($this->tax_rate / 100);
            $single_invoices->total_with_tax = $discounted_price + $single_invoices->tax_value;
            $single_invoices->type = $this->type;
            $single_invoices->save();
            $this->invoiceSave = true;
            $this->show_table = true;
            session()->flash('success', 'Invoice created successfully!');

        } catch (Exception $e) {
            session()->flash('error', 'Error: '.$e->getMessage());
        }

    }
}
