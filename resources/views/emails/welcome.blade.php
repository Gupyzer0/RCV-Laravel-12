@component('mail::message')
# ¡Hola {{ $user->name }}!

Bienvenido a nuestro equipo. Estamos encantados de tenerte con nosotros.

Puedes acceder a tu cuenta haciendo clic en el siguiente botón:

@component('mail::button', ['url' => url('/')])
Iniciar Sesión
@endcomponent

**Videos Instructivos:**  

<table width="100%" style="margin: 10px 0; text-align: center;">
    <tr>
        <td style="padding: 5px;">
            <a href="youtube.com/poliza" style="background-color: #3d85c6; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; display: inline-block;">
                ¿Qué es líder de seguros?
            </a>
        </td>
        <td style="padding: 5px;">
            <a href="youtube.com/poliza" style="background-color: #6aa84f; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; display: inline-block;">
                Cómo realizar una póliza
            </a>
        </td>
        <td style="padding: 5px;">
            <a href="youtube.com/poliza" style="background-color: #e69138; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; display: inline-block;">
                Cómo generar un reporte
            </a>
        </td>
    </tr>
</table>

Gracias por unirte a nosotros. Si tienes alguna pregunta o necesitas ayuda, no dudes en contactar a nuestro equipo de soporte.  
Si deseas cambiar tu contraseña, puedes hacerlo desde tu perfil una vez que inicies sesión.

@endcomponent