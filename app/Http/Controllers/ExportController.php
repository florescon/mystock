<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Quotation;
use App\Models\Product;
use App\Models\Service;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\Supplier;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;
use Milon\Barcode\Facades\DNS1DFacade;
use PDF;

class ExportController extends Controller
{
    /**
     * Return a response with the PDF to show in the browser
     *
     * @param mixed $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function salePos($id): Response
    {
        $sale = Sale::with('saleDetails', 'saleDetailsService.customer')->where('id', $id)->firstOrFail();

        $data = [
            'sale' => $sale,
        ];

        $pdf = PDF::loadView('admin.sale.print-pos', $data, [], [
            // 'format' => 'a5',
            'format' => [80, 270]
        ]);

        return $pdf->stream(__('Sale').$sale->reference.'.pdf');
    }


    public function ddd(?string $products = null)
    {
        $barcodes = [];
        $productsJson = [];

        // $productsjson = Product::whereIn('id', json_decode($products))->get();

        foreach(json_decode($products) as $product){
            $prod=Product::where('id',$product->id)->first();
            $prod->quantity = $product->q;
            $prod->price = $product->price;
            if($prod){
                array_push($productsJson,$prod);
            }
        }

        // dd($productsJson);

        foreach ($productsJson as  $product) {
            $quantity = $product->quantity ?? 1;
            $name = $product->name;
            $price = $product->price;
            for ($i = 0; $i < $quantity; $i++) {
                $barcode = DNS1DFacade::getBarCodeSVG(
                    $product->code, 
                    $product->barcode_symbology, 
                    1, 35, 'black', false);

                array_push($barcodes, ['barcode' => $barcode, 'name' => $product->name, 'price' => $product->price]);
            }
        }

        $data = [
            'barcodes' => $barcodes,
        ];

        $stylesheet = file_get_contents(public_path('print/bootstrap.min.css'));

        $pdf = PDF::loadView('admin.barcode.print', $data, [], [
            // 'format' => 'a5',
            'margin_left'                => 2,
            'margin_right'               => 2,
            'margin_top'                 => 2,
            'margin_bottom'              => 2,
            'format' => [50, 25]
        ]);


        $pdf->getMpdf()->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        return $pdf->stream('barcodes-'.date('Y-m-d').'.pdf');

        return redirect()->route('sales.dd', urlencode(json_encode($ordercollection)));

    }

    public function sale($id): Response
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $customer = Customer::where('id', $sale->customer->id)->firstOrFail();

        $data = [
            'sale'     => $sale,
            'customer' => $customer,
            'logo'     => $this->getCompanyLogo(),
        ];

        $pdf = PDF::loadView('admin.sale.print', $data, [], [
            'format'    => 'a4',
            'watermark' => $this->setWaterMark($sale),
        ]);

        return $pdf->stream(__('Sale').$sale->reference.'.pdf');
    }

    public function inscriptionPrint(): Response
    {
        $settings = Setting::firstOrFail();

        $data = [
            'logo'     => $this->getCompanyLogo(),
            'inscription' => $settings->inscription,
        ];

        $pdf = PDF::loadView('admin.inscription.print', $data, [], [
            'format'    => 'a4',
        ]);

        return $pdf->stream(__('Inscription').'.pdf');
    }

    public function serviceFormat()
    {
        $services = Service::query()->limit(6)->get();

        return view('admin.service.format', compact('services'));
    }

    public function formatOne(?string $services = null)
    {
        $barcodes = [];
        $servicesJson = [];
        // dd($comment);

        foreach(json_decode($services) as $service){
            $prod=Service::where('id',$service)->first();
            if($prod){
                array_push($servicesJson, $prod);
            }
        }

        $services = $servicesJson;

        return view('admin.service.format.format-one', compact('services'));
    }

    public function formatTwo(?string $services = null)
    {
        $barcodes = [];
        $servicesJson = [];
        // dd($comment);

        foreach(json_decode($services) as $service){
            $prod=Service::where('id',$service)->first();
            if($prod){
                array_push($servicesJson, $prod);
            }
        }

        $services = $servicesJson;

        return view('admin.service.format.format-two', compact('services'));
    }

    public function formatThree(?string $services = null)
    {
        $barcodes = [];
        $servicesJson = [];
        // dd($comment);

        foreach(json_decode($services) as $service){
            $prod=Service::where('id',$service)->first();
            if($prod){
                array_push($servicesJson, $prod);
            }
        }

        $services = $servicesJson;

        return view('admin.service.format.format-three', compact('services'));
    }

    public function purchaseReturns($id): Response
    {
        $purchaseReturn = PurchaseReturn::where('id', $id)->firstOrFail();
        $supplier = Supplier::where('id', $purchaseReturn->supplier->id)->firstOrFail();

        $data = [
            'purchase_return' => $purchaseReturn,
            'supplier'        => $supplier,
        ];

        $pdf = PDF::loadView('admin.purchasesreturn.print', $data);

        return $pdf->stream(__('Purchase Return').$purchaseReturn->reference.'.pdf');
    }

    public function quotation($id): Response
    {
        $quotation = Quotation::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $quotation->customer->id)->firstOrFail();

        $data = [
            'quotation' => $quotation,
            'customer'  => $customer,
        ];

        $pdf = PDF::loadView('admin.quotation.print', $data, [], [
            'format' => 'a4',
        ]);

        return $pdf->stream(__('Quotation').$quotation->reference.'.pdf');
    }

    public function purchase($id): Response
    {
        $purchase = Purchase::with('supplier', 'purchaseDetails')->where('id', $id)->firstOrFail();
        $supplier = Supplier::where('id', $purchase->supplier->id)->firstOrFail();

        $data = [
            'purchase' => $purchase,
            'supplier' => $supplier,
            'logo'     => $this->getCompanyLogo(),
        ];

        $pdf = PDF::loadView('admin.purchases.print', $data, [], [
            'format' => 'a5',
        ]);

        return $pdf->stream(__('Purchase').$purchase->reference.'.pdf');
    }

    public function saleReturns($id): Response
    {
        $saleReturn = SaleReturn::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $saleReturn->customer->id)->firstOrFail();

        $data = [
            'sale_return' => $saleReturn,
            'customer'    => $customer,
        ];

        $pdf = PDF::loadView('admin.salesreturn.print', $data);

        return $pdf->stream(__('Sale Return').$saleReturn->reference.'.pdf');
    }

    private function getCompanyLogo()
    {
        return public_path('images/logo.png');
        // return 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/logo.png')));
    }

    private function setWaterMark($model)
    {
        return $model && $model->status ? $model->status : '';
    }
}
