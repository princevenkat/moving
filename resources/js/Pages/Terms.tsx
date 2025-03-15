import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Terms() {
  return (
    <AuthenticatedLayout>
      <Head title="Terms and Conditions" />
      <div className="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h1 className="text-2xl font-bold">Terms and Conditions</h1>
        <p className="mt-4 text-gray-600">
          By using this service, you agree to our terms and conditions...
        </p>
      </div>
    </AuthenticatedLayout>
  );
}
