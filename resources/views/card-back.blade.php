<svg version="1.1" viewBox="0 0 149.74 92.544" xml:space="preserve"
    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="transform: rotateY({{ old('type') == $type ? '0' : '90deg' }});">
    <defs>
        <pattern id="a" width="30.066020" height="5.1805778" patternUnits="userSpaceOnUse">
            <path
                d="m7.597 0.061c-2.518-0.248-4.941 0.241-7.607 1.727v1.273c2.783-1.63 5.183-2.009 7.482-1.781 2.298 0.228 4.497 1.081 6.781 1.938 4.567 1.713 9.551 3.458 15.813-0.157l-4e-3 -1.273c-6.44 3.709-10.816 1.982-15.371 0.273-2.278-0.854-4.576-1.75-7.094-2z" />
        </pattern>
    </defs>
    <g transform="translate(-37 -164.38)">
        <rect x="37" y="164.38" width="149.74" height="92.544" ry="8.6571" fill="#090909"
            stop-color="#161617cc" stroke-miterlimit="7.1" stroke-width="1.572" style="paint-order:fill markers stroke" />
        <rect x="50.043" y="202.17" width="129.53" height="17.691" ry="0" fill="#585858"
            stop-color="#161617cc" style="-inkscape-stroke:none;paint-order:fill markers stroke" /><text x="150.31099"
            y="213.51271" fill="#ffffff" font-size="7.1412px" letter-spacing=".48683px" stroke-width=".17853"
            style="line-height:1.25" xml:space="preserve">
            <tspan id="{{ $prefix }}-card-security" x="150.31099" y="213.51271" fill="#ffffff" font-family="Montserrat" stroke-width=".17853">{{ old('card_security_code') ?? '123' }}</tspan>
        </text>
        <rect x="37" y="177.95" width="149.74" height="17.691" ry="0" fill="#333"
            stop-color="#161617cc" style="-inkscape-stroke:none;paint-order:fill markers stroke" />
        <rect x="50.043" y="202.17" width="94.904" height="17.691" ry="0" fill="url(#a)"
            stop-color="#161617cc" style="paint-order:fill markers stroke" />
    </g>
</svg>
