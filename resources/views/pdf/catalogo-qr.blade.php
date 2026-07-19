<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Propuesta de Logo y Estilos QR - WalkyDog</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap');
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .title {
            font-size: 26px;
            font-weight: 800;
            color: #d35400;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
            font-weight: 600;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            border-left: 4px solid #d35400;
            padding-left: 10px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .grid {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        
        .grid td {
            width: 50%;
            padding: 15px;
            vertical-align: middle;
            border: 1px solid #f1f5f9;
            background-color: #fafafa;
            border-radius: 12px;
        }
        
        .image-container {
            text-align: center;
        }
        
        .mockup-img {
            max-width: 200px;
            height: auto;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            border: 3px solid #ffffff;
        }
        
        .description-cell {
            padding-left: 20px;
        }
        
        .style-name {
            font-size: 16px;
            font-weight: 700;
            color: #d35400;
            margin: 0 0 10px 0;
        }
        
        .style-desc {
            font-size: 12px;
            color: #475569;
            margin: 0;
            line-height: 1.6;
        }
        
        .badge {
            display: inline-block;
            background-color: #ffedd5;
            color: #ea580c;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 9999px;
            margin-top: 10px;
            text-transform: uppercase;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">WalkyDog Premium Brand Dossier</div>
        <div class="subtitle">Propuesta de Logotipo e Identidad de Códigos QR</div>
    </div>

    <div class="section-title">1. Propuesta de Nuevo Logotipo de la Aplicación</div>
    
    <table class="grid">
        <tr>
            <td class="image-container" style="width: 40%;">
                <img src="{{ public_path('images/walkydog_new_logo_1784504282342.jpg') }}" class="mockup-img" style="max-width: 180px;">
            </td>
            <td class="description-cell" style="width: 60%;">
                <h3 class="style-name">Logo WalkyDog Minimalista Premium</h3>
                <p class="style-desc">
                    Un logotipo moderno que fusiona la silueta abstracta de un perro feliz en movimiento y una correa integrada, utilizando la paleta cromática corporativa: Naranja Vibrante (energía, mascotas, dinamismo) y Azul Marino Profundo (confianza, seguridad, profesionalismo). Diseñado bajo proporciones geométricas limpias para encajar perfectamente como icono de aplicación móvil y favicon.
                </p>
                <span class="badge">Recomendado</span>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <div class="section-title">2. Catálogo de Estilos de Códigos QR</div>
    <p style="font-size: 12px; color: #64748b; margin-bottom: 20px;">
        A continuación se presentan tres alternativas visuales para estilizar los códigos QR de validación en pantalla, alejándonos del patrón plano tradicional y adaptándolos a la temática canina.
    </p>

    <!-- Estilo 1 -->
    <table class="grid" style="margin-bottom: 20px;">
        <tr>
            <td class="image-container" style="width: 40%;">
                <img src="{{ public_path('images/qr_style_instagram_nametag_1784504291936.jpg') }}" class="mockup-img">
            </td>
            <td class="description-cell" style="width: 60%;">
                <h3 class="style-name">Estilo A: Instagram Nametag</h3>
                <p class="style-desc">
                    Inspirado en las etiquetas de identificación de Instagram. Cuenta con un fondo degradado naranja-rosado de alta energía, módulos de puntos circulares redondeados y el isotipo de una huellita blanca en el centro. Aporta un aire sumamente juvenil, tecnológico y fresco al aplicativo.
                </p>
                <span class="badge">Estilo Juvenil</span>
            </td>
        </tr>
    </table>

    <!-- Estilo 2 -->
    <table class="grid" style="margin-bottom: 20px;">
        <tr>
            <td class="image-container" style="width: 40%;">
                <img src="{{ public_path('images/qr_style_dog_silhouette_1784504300995.jpg') }}" class="mockup-img">
            </td>
            <td class="description-cell" style="width: 60%;">
                <h3 class="style-name">Estilo B: Silueta de Perro Juguetón</h3>
                <p class="style-desc">
                    El código QR completo adopta o se enmarca dentro de la silueta abstracta de la cabeza de un perrito. Combina módulos cuadrados clásicos con bordes externos troquelados. Ideal para enfatizar el amor por las mascotas de forma directa e interactiva.
                </p>
                <span class="badge">Estilo Temático</span>
            </td>
        </tr>
    </table>

    <!-- Estilo 3 -->
    <table class="grid" style="margin-bottom: 20px;">
        <tr>
            <td class="image-container" style="width: 40%;">
                <img src="{{ public_path('images/qr_style_neon_glow_1784504310424.jpg') }}" class="mockup-img">
            </td>
            <td class="description-cell" style="width: 60%;">
                <h3 class="style-name">Estilo C: Glow Neón Dark Mode</h3>
                <p class="style-desc">
                    Diseño de alto contraste optimizado para entornos de baja luminosidad y pantallas OLED. Los módulos de datos brillan con efectos de neón en tonos naranja y cian eléctrico sobre un fondo azul oscuro. Otorga un aspecto futurista e innovador al flujo de validación.
                </p>
                <span class="badge">Estilo Futurista</span>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dossier de Identidad WalkyDog &copy; {{ date('Y') }} - Seminario de Ingeniería de Sistemas
    </div>

</body>
</html>
