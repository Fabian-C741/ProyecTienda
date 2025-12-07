/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  images: {
    domains: ['via.placeholder.com', 'ingreso-tienda.kcrsf.com'],
  },
  env: {
    NEXT_PUBLIC_API_URL: 'https://ingreso-tienda.kcrsf.com/api/v1',
  },
}

module.exports = nextConfig
