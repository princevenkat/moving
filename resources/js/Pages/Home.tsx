import { PageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
export default function Home({
    auth,
    laravelVersion,
    phpVersion,
}: PageProps<{ laravelVersion: string; phpVersion: string }>) {
    const handleImageError = () => {
        document
            .getElementById('screenshot-container')
            ?.classList.add('!hidden');
        document.getElementById('docs-card')?.classList.add('!row-span-1');
        document
            .getElementById('docs-card-content')
            ?.classList.add('!flex-row');
        document.getElementById('background')?.classList.add('!hidden');
    };





    return (
        <AuthenticatedLayout>
            <Head title="Home" />
          <div className="max-w-7xl mx-auto py-16">

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-4 m-3">


              <Link href={route('inquiry.start', { service_type: 'moving' })}>
                <div className="card bg-base-100  shadow-xl">

                  <div className="card-body flex flex-row flex-wrap justify-between items-center">
                    <div>
                      <img src='assets/moving.svg' alt='' className='w-12' />
                      <h2 className="font-semibold text-lg">Only moving</h2>
                    </div>
                    <img src="assets/right-arrow.svg" alt="" className='w-6'/>
                  </div>
                </div>
                </Link>


            </div>




          </div>
        </AuthenticatedLayout>
    );
}
