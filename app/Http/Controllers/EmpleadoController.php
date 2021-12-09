<?php

namespace App\Http\Controllers;

use App\Models\empleado;

use App\Models\puesto;

use App\Models\departamento;

use App\Http\Requests\EmpleadoCreateRequest;
use App\Http\Requests\EmpleadoEditRequest;

use DB;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=[];
        
    
        $data['empleados'] = empleado::all();
        
      
        return view('empleado.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $data['puestos'] = puesto::all();
          $data['departamentos'] = departamento::all();
        return view('empleado.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadoCreateRequest $request)
    {
        //
        
         $empleado = new empleado($request->all());
         
        $data=[];
        $data['message'] = 'New employee ("' . $empleado->nombre . '")  has been insert successfully';
        $data['type'] = 'success';
        $id=0;
        try {
            /*departamento::where('idempleadojefe', $empleado->idempleadojefe)->update(['idempleadojefe'=>$id]); */
            $result=$empleado->save();
        
       
        } catch(\Exception $e) {
              $result=false;
           return back()->withInput()->with($data);
        }
       if(!$result){
            $data['message'] = 'The employee can not be inserted ';
            $data['type'] = 'danger';
            // el withinput le devolvemos lo que ha puesto, para que no los tenga que volver a poner
            return back()->withInput()->with($data);
        }
        
        return redirect('empleado')->with($data);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(empleado $empleado)
    {
        //
        $data=[];
        $data['puestos']= puesto::all();
        $data['departamentos']= departamento::all();
        $data['empleado']= $empleado;
       

        return view ('empleado.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(empleado $empleado)
    {
        //
        $data=[];
        $data['empleado']= $empleado;
        $data['puestos'] = puesto::all();
        $data['departamentos'] = departamento::all();
        
        return view ('empleado.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(EmpleadoEditRequest $request, empleado $empleado)
    {
        //
        
         
        // // HAY  QUE HACER LA VALIDACION
        
        $data=[];
        $data['message'] = 'Employee  "'. $empleado->nombre .'" has been updated successfully';
        $data['type'] = 'success';
        
        try {
           
            $result=$empleado->update($request->all() );
            // enseñaría true en  $result = $place->save();
            //dd($result);
        } catch(\Exception $e) {
            $result=false;
            
            //$data['message'] = 'The place can not be inserted because it has a repeated name';
            //$data['type'] = 'danger';
       
        }
        if(!$result){
            $data['message'] = 'The employee can not be updated ';
            $data['type'] = 'danger';
       
            return back()->withInput()->with($data);
        }
        
        return redirect('empleado')->with($data);
        
        
         // PONER EN LA PRACTICA QUE HAGA, QUE CUNADO NO SE CAMBIE NADA, SE QUEDE EN LA PAGINA DEL EDIT Y MUESTRE UN MENSAJE
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(empleado $empleado)
    {
         
       $departamentos = departamento::all();
       $id= $empleado->id;
       $id1=0;
       foreach($departamentos as $departamento){
           if($departamento->idempleadojefe == $empleado->id){
               DB::table('departamento')->where('departamento.idempleadojefe', '=' , $id)->update(['departamento.idempleadojefe' => null]);
           }
       }
       
       
        try{
            $empleado->delete();  
             $data=[];                              
        $data['message']= 'The employment ' . $empleado->nombre . ' has been removed';
        $data['type']= 'success';
            return redirect('empleado')->with($data);
            
        }catch (Exception $e){
            $data['message']= 'The employment ' . $empleado->nombre . ' not has been removed becouse is a boss';
            $data['type']= 'danger';
             return back()->withInput()->with($data);
        }
        
        
    return redirect('empleado')->with($data);
    }
    
     public function destroyShow(empleado $empleado, puesto $puesto)
    {
      $departamentos = departamento::all();
        $data=[];
        $data['message']= 'The employment ' . $empleado->nombre . ' has been removed';
        $data['type']= 'success';
       $id= $empleado->id;
       $id1=0;
       foreach($departamentos as $departamento){
           if($departamento->idempleadojefe == $empleado->id){
               DB::table('departamento')->where('departamento.idempleadojefe', '=' , $id)->update(['departamento.idempleadojefe' => null]);
           }
       }
        try{
            $empleado->delete();    
               return redirect('puesto/' . $puesto->id )->with($data);
        }catch (Exception $e){
            $data['message']= 'The employment ' . $empleado->nombre . ' not has been removed';
            $data['type']= 'danger';
             return back()->withInput()->with($data);
     
        }
        
        
    }
    
    public function destroyShowDepartment(empleado $empleado, departamento $departamento)
    {
      $departamentos = departamento::all();
        $data=[];
        $data['message']= 'The employment ' . $empleado->nombre . ' has been removed';
        $data['type']= 'success';
       $id= $empleado->id;
       $id1=0;
       foreach($departamentos as $departamento){
           if($departamento->idempleadojefe == $empleado->id){
               DB::table('departamento')->where('departamento.idempleadojefe', '=' , $id)->update(['departamento.idempleadojefe' => null]);
           }
       }
        try{
            $empleado->delete();    
        }catch (Exception $e){
            $data['message']= 'The employment ' . $empleado->nombre . ' not has been removed';
            $data['type']= 'danger';
        }
        
        
        return redirect('departamento/' . $departamento->id )->with($data);
    }
    
    
    
    function flush(){ // 
         
         // Borrar los datos de la tabla entera
      
        try {
        empleado::query()->delete();
        $data['message']= 'You have successfully deleted all employments!';
        $data['type']= 'success';
        return redirect('empleado')->with($data);
        } catch(\Exception $e) {
         $data['message']= 'Have a problem with delete all employees!';
        $data['type']= 'danger';
         return back()->withInput()->with($data);
        }
     }
     
     function destroyPuestos(Request $request, puesto $puesto){ // 
        
        try {
        empleado::where('idpuesto', $puesto->id)->delete();
    
        $data['message']= 'You have successfully deleted all employments!';
        $data['type']= 'success';
         // Borrar los datos de la tabla entera
     
         return redirect('puesto/'. $puesto->id)->with($data);
        } catch(\Exception $e) {

        $data['message']= 'All could not be deleted!';
        $data['type']= 'danger';
        return back();
        }
        

     }
     
     function destroyDepartamentos(Request $request, departamento $departamento){ // 
     
        try {
        empleado::where('iddepartamento', $departamento->id)->delete();
      
        $data['message']= 'You have successfully deleted all departments!';
        $data['type']= 'success';
         // Borrar los datos de la tabla entera
         return redirect('departamento/'. $departamento->id)->with($data);
        } catch(\Exception $e) {

        $data['message']= 'All could not be deleted!';
        $data['type']= 'danger';
        return back();
        }
        

     }
}
