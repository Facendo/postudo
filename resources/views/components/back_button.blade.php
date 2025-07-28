<style>
.button_body {
    /* Base styles */
    padding: 16px 35px; /* Slightly more padding for a larger touch target */
    color: #ffffff;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); /* Blue gradient for a modern feel */
    border: none;
    border-radius: 50px; /* Fully rounded, pill shape */
    font-family: 'Montserrat', sans-serif; /* Changed to Montserrat for consistency with headings, or keep Inter if preferred for body text */
    font-size: 19px; /* Slightly larger font */
    font-weight: 700; /* Bolder text */
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); /* More pronounced shadow for depth */
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Smoother, more engaging transition */
    letter-spacing: 0.8px; /* Increased letter spacing for a cleaner look */
    display: inline-flex; /* Use inline-flex for better flow with text/other elements */
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    position: relative; /* Needed for pseudo-elements for effects */
    overflow: hidden; /* Ensures content stays within rounded borders */
    z-index: 1; /* For layering effects */
}

.button_body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.15); /* Subtle white overlay for hover effect */
    transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    transform: scaleX(0);
    transform-origin: bottom right;
    z-index: -1;
}

.button_body:hover::before {
    transform: scaleX(1);
    transform-origin: bottom left;
}

.button_body:hover {
    background: linear-gradient(135deg, #0056b3 0%, #003d80 100%); /* Darker blue on hover */
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.35); /* Even more pronounced shadow */
    transform: translateY(-5px); /* More lift on hover */
}

.button_body:active {
    background: #003d80; /* Solid darker blue on click */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Reduced shadow for pressed state */
    transform: translateY(0); /* Sinks back down */
    transition: all 0.1s ease; /* Quicker transition for active state */
}

</style>
<a href="{{ url()->previous() }}" class="button_body">Volver</a>