<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Producer;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
   /**
    * Display a listing of the Producers
    */
   public function index()
   {
      $producers = Producer::all();

      return view('admin.producers.index', compact('producers'));
   }
   
   /**
    * Display Producer creation form
    */
   public function create()
   {
      return view('admin.producers.create');
   }

   /**
    * Create Producer
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function store(Request $request)
   {
      $producerModel = new Producer();

      $postData = $request->post();
      $setProducerData = [
         'name' => ucfirst($postData['name']),
         'address' => ucfirst($postData['address']),
         'contacts' => $postData['contacts'],
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $producerModel->storeProducer($setProducerData);

      return redirect()->route('admin.producer');
   }

   /**
    * Display Producer update form
    */
   public function edit($idProducer)
   {
      $producer = Producer::find($idProducer);

      return view('admin.producers.update', compact('producer'));
   }

   /**
    * Update Producer
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function update(Request $request)
   {
      $producerModel = new Producer();

      $postData = $request->post();
      $setProducerData = [
         'name' => ucfirst($postData['name']),
         'address' => ucfirst($postData['address']),
         'contacts' => $postData['contacts'],
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $idProducer = $request->post('id_producer');
      $producerModel->updateProducer($idProducer, $setProducerData);

      return redirect()->route('admin.producer');
   }

   /**
    * Delete Producer
    */
   public function destroy(Request $request)
   {
      $producerModel = new Producer();

      $idProducer = $request->post('id_producer');
      $producerModel->deleteProducer($idProducer);

      return redirect()->route('admin.producer');
   }
}
