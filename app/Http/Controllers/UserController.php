<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    /**
     * TODO: Revisar este metodo . . . creo que deja modificar la data de cualquier otro usuario . . .
     */
    public function profile ($id)
    {
        $user = User::findOrFail($id);
        return view('user-modules.Perfil.profile', compact('user'));
    }

    public function upload_profile(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'image' => ['mimes:jpeg,jpg', 'max:4096']
            ], [
                'image.image' => 'El archivo debe ser una imagen.',
                'image.mimes' => 'El archivo debe estar en formato jpeg o jpg.',
                'image.max' => 'El archivo no debe exceder 4MB.'
            ]
        );

        $user = User::findOrFail($id);

        // Crear la carpeta si no existe
        $uploadPath = public_path('uploads/fotosperfil/' . $user->id);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'Profile_' . preg_replace('/\D/', '', $user->client_ci) . '.' . $extension;
            $imagePath = $uploadPath . '/' . $imageName;

            // Eliminar la imagen anterior si existe
            if (!empty($user->image) && file_exists($uploadPath . '/' . $user->image)) {
                unlink($uploadPath . '/' . $user->image);
            }

            // Guardar la nueva imagen
            $image->move($uploadPath, $imageName);
            $user->image = $imageName;
            $user->save(); // Guarda los cambios en la base de datos
        }

        return redirect()->back()->with('success', 'Imagen Cargada Correctamente');
    }

    public function download_profile($id)
    {
        $user = User::findOrFail($id);
    
        // Construir la ruta completa al archivo
        $filePath = public_path('uploads/fotosperfil/' . $user->id . '/' . $user->image);
    
        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }
    
        // Descargar el archivo
        return response()->download($filePath);
    }

}
