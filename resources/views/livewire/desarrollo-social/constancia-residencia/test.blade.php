<div class="min-h-screen flex items-center justify-center bg-[#F3F4F6] p-4">

    <flux:card class="w-full max-w-2xl rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">

        {{-- Header --}}
        <div class="text-center mb-8">

            <flux:avatar 
                src="{{ asset('imagenes/icono_muni.png') }}" 
                size="xl"
                class="mx-auto mb-4"
            />

            <h1 class="text-[#030EA7] text-xl md:text-2xl font-bold tracking-[0.2em] uppercase leading-tight">
                Constancia de Residencia
            </h1>

            <p class="text-gray-500 text-sm mt-2">
                Complete la información requerida para registrar su solicitud
            </p>

        </div>


        <div class="flex justify-center gap-4 my-6">

        {{--  INDICADORES DE PASOS --}}
        <div
        class="w-8 h-8 rounded-full cursor-pointer flex items-center justify-center border-2"
        >
        1
       </div>

        <!-- Paso 2 -->
        <div
            
            class="w-8 h-8 rounded-full cursor-pointer flex items-center justify-center border-2"
          
         >
            2
        </div>


        <!-- Paso 3 -->
            <div
          
            class="w-8 h-8 rounded-full cursor-pointer flex items-center justify-center border-2"
        >
            3
        </div>

        </div>


        <p class="mb-5 text-red-600 text-center text-sm mt-1 bg-yellow-100 p-2 rounded">
                    Ingrese los nombres y apellidos tal como aparecen en el DPI
        </p>


        {{-- INPUTS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input label="Nombres" placeholder="Ingrese sus nombres"
             />


             <flux:input label="Apellidos" placeholder="Ingrese sus apellidos"
             />

             <flux:input label="Email" placeholder="Ingrese sus email"
             />

             <flux:input label="Télefono" placeholder="Ingrese su telefono"
             />

             <flux:input label="DPI" placeholder="Ingrese su DPI"
             />

             <flux:input label="Zona" placeholder="Ingrese su Zona"
             />

              <flux:input label="Domiclio" placeholder="Ingrese su domicilio"
             />

        </div>

   



        {{-- Form grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            {{-- Aquí irán los inputs --}}
        </div>

        {{-- Botón --}}
        <div class="mt-9 flex justify-end">
            {{-- botón flux --}}
            <flux:button variant="primary">
                Siguiente
            </flux:button>
        </div>

    </flux:card>

</div>