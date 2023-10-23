<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Producer;
use App\Models\Site\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    /**
     * Display a listing of the Producers
     *
     * @return View
     */
    public function index(): View
    {
        $producerModel = new Producer();

        $producers = $producerModel->readAllProducers();

        return view('admin.producers.index', compact('producers'));
    }

    /**
     * Display Producer creation form
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.producers.create');
    }

    /**
     * Create Producer
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('createProducer')) {
            $producerModel = new Producer();

            $setProducerData = [
                'name' => ucfirst($request->post('name')),
                'address' => ucfirst($request->post('address')),
                'contacts' => $request->post('contacts'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $producerModel->storeProducer($setProducerData);
        }

        return redirect()->route('admin.producer');
    }

    /**
     * Display Producer update form
     *
     * @param int $idProducer
     * @return View
     */
    public function edit(int $idProducer): View
    {
        $producerModel = new Producer();

        $producer = $producerModel->readProducer($idProducer);

        return view('admin.producers.update', compact('producer'));
    }

    /**
     * Update Producer
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        if ($request->has('updateProducer')) {
            $producerModel = new Producer();

            $setProducerData = [
                'name' => ucfirst($request->post('name')),
                'address' => ucfirst($request->post('address')),
                'contacts' => $request->post('contacts'),
            ];
            $idProducer = $request->post('id_producer');
            $producerModel->updateProducer($idProducer, $setProducerData);
        }

        return redirect()->route('admin.producer');
    }

    /**
     * Delete Producer & all its Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteProducer')) {
            $producerModel = new Producer();
            $productModel = new Product();

            $idProducer = $request->post('id_producer');
            $productModel->deleteProducerProducts($idProducer);
            $producerModel->deleteProducer($idProducer);
        }

        return back();
    }

    /**
     * Restore Producer& all its Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        if ($request->has('restoreProducer')) {
            $producerModel = new Producer();
            $productModel = new Product();

            $idProducer = $request->post('id_producer');
            $productModel->restoreProducerProducts($idProducer);
            $producerModel->restoreMarketplace($idProducer);
        }

        return back();
    }
}
