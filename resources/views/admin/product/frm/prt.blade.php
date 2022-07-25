<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="row">
    <div class="col-md-12">
        <section class="panel">                        
            <div class="panel-body">

              @if ( !empty ( $product->id) )

                <div class="form-group">
                  <label for="nombre" class="negrita">Nombre:</label>                          
                  <div>
                    <input class="form-control" placeholder="Jugo de Fresa" required="required" name="nombre" type="text" id="nombre" value="{{ $products->nombre }}">                              
                  </div>
                </div>

                <div class="form-group">
                  <label for="precio" class="negrita">Precio:</label>                          
                  <div>
                    <input class="form-control" placeholder="4.50" required="required" name="precio" type="text" id="precio" value="{{ $products->precio }}">                              
                  </div>
                </div>

                <div class="form-group">
                  <label for="stock" class="negrita">Description:</label>                          
                  <div>
                    <input class="form-control" placeholder="40" required="required" name="stock" type="text" id="stock" value="{{ $products->stock }}">                              
                  </div>
                </div>

                <div class="form-group">
                  <label for="img" class="negrita">Selecciona una imagen:</label>                         
                  <div>
                  <input name="img[]" type="file" id="img" multiple="multiple">
                  <br>
                  <br>
                  @if ( !empty ( $products->imagenes) )

                    <span>Imagen(es) Actual(es): </span>
                    <br>

                    <!-- Mensaje: Imagen Eliminada Satisfactoriamente ! -->
                    @if(Session::has('message'))
                      <div class="alert alert-primary" role="alert">
                        {{ Session::get('message') }}
                      </div>
                    @endif

                    <!-- Mostramos todas las imágenes pertenecientesa a este registro -->
                    @foreach($imagenes as $img)                    
                        
                        <img src="../../../uploads/{{ $img->nombre }}" width="200" class="img-fluid"> 

                        <!-- Botón para Eliminar la Imagen individualmente -->
                        <a href="{{ route('admin/products/eliminarimagen', [$img->id, $products->id]) }}" class="btn btn-danger btn-sm" onclick="return confirmarEliminar();">Eliminar</a> 

                    @endforeach

                    

                  @else

                    Aún no se ha cargado una imagen para este producto

                  @endif                
                  </div>

                </div>

              @else

                <div class="form-group">
                  <label for="nombre" class="negrita">Name:</label>                          
                  <div>
                    <input class="form-control" placeholder="" required="required" name="nombre" type="text" id="nombre">                              
                  </div>
                </div>

                <div class="form-group">
                  <label for="precio" class="negrita">Price:</label>                          
                  <div>
                    <input class="form-control" placeholder="2500.00" required="required" name="precio" type="text" id="precio">                              
                  </div>
                </div>

                <div class="form-group">
                  <label for="stock" class="negrita">Description:</label>                          
                  <div>
                    <input class="form-control" placeholder="" required="required" name="stock" type="text" id="stock">                              
                  </div>
                </div>

                <div class="form-group">
                  <label for="img" class="negrita">Selecciona una imagen:</label>
                  <div>
                    <input name="img[]" type="file" id="img" multiple="multiple" required>               
                  </div>
                </div>

              @endif

                <button type="submit" class="btn btn-info">Guardar</button>
                <a href="{{ route('index') }}" class="btn btn-warning">Cancelar</a>

                <br>
                <br>
              
            </div>
        </section>
    </div>
</div>