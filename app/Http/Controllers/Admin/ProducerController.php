<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProducerRequest;
use App\Models\Producer;
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
        $producers = Producer::withTrashed()->get();

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
     * @param ProducerRequest $request
     * @return RedirectResponse
     */
    public function store(ProducerRequest $request): RedirectResponse
    {
        Producer::create($request->validated());

        return redirect()->route('admin.producer');
    }

    /**
     * Display Producer update form
     *
     * @param int $idProducer
     * @return View|RedirectResponse
     */
    public function edit(int $idProducer): View|RedirectResponse
    {
        $producer = Producer::findOrFail($idProducer);

        return view('admin.producers.update', compact('producer'));
    }

    /**
     * Update Producer
     *
     * @param ProducerRequest $request
     * @return RedirectResponse
     */
    public function update(ProducerRequest $request): RedirectResponse
    {
        $idProducer = $request->validate(['id_producer' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_producer'];
        $producer = Producer::findOrFail($idProducer);
        $producer->fill($request->validated());
        if ($producer->isDirty()) {
            $producer->save();
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
        $idProducer = $request->validate(['id_producer' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_producer'];
        Producer::findOrFail($idProducer)->delete();

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
        $idProducer = $request->validate(['id_producer' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_producer'];
        Producer::onlyTrashed()->findOrFail($idProducer)->restore();

        return back();
    }
}
