<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> 
            إدارة صلاحيات الأزرار حسب نوع المستخدم 
        </h2> 
    </x-slot> 

    <div class="py-12"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            @if(session('success')) 
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded shadow">
                    {{ session('success') }} 
                </div> 
            @endif 

            <div class="overflow-x-auto bg-white shadow rounded-lg p-4"> 
                <form action="{{ route('permissions.update') }}" method="POST"> 
                    @csrf 

                    <table class="w-full table-auto border text-sm text-right"> 
                        <thead class="bg-gray-100"> 
                            <tr> 
                                <th class="p-2 border">الصلاحية</th> 
                                @foreach($roles as $role) 
                                    <th class="p-2 border">{{ $role->name }}</th> 
                                @endforeach 
                            </tr> 
                        </thead> 
                        <tbody> 
                            @foreach($permissions as $permission) 
                            <tr class="hover:bg-gray-50"> 
                                <td class="p-2 border font-medium">{{ $permission->name }}</td> 
                                @foreach($roles as $role) 
                                    <td class="p-2 border text-center"> 
                                        <input type="checkbox" name="permission_{{ $permission->id }}[]" value="{{ $role->id }}" 
                                        {{ $permission->roles->contains('id', $role->id) ? 'checked' : '' }}> 
                                    </td> 
                                @endforeach 
                            </tr> 
                            @endforeach 
                        </tbody> 
                    </table> 

                    <div class="text-left mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                            حفظ التعديلات
                        </button>
                    </div>
                </form> 
            </div>
        </div> 
    </div> 
</x-app-layout> 
