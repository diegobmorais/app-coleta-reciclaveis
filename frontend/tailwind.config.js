/** @type {import('tailwindcss').Config} */
export default{
  darkMode: ["class"],
  content: [    
    "./src/**/*.js",
    "./src/**/*.vue",
    "*.{js,ts,jsx,tsx,mdx}",
    "app/**/*.{ts,tsx}",
    "components/**/*.{ts,tsx}",
  ],  
  plugins: [
    require("@tailwindcss/forms"),    
  ],
}
