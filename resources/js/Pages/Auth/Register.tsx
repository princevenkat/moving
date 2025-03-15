import InputError from '@/Components/Core/InputError';
import InputLabel from '@/Components/Core/InputLabel';
import PrimaryButton from '@/Components/Core/PrimaryButton';

import TextInput from '@/Components/Core/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Checkbox from "@/Components/Core/Checkbox";


export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        last_name: '',
        gender: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false as boolean,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Register" />

          <div className='bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg max-w-[420px] mt-6 mx-auto '>
            <div >
              <form onSubmit={submit}>
                <div>
                  <InputLabel htmlFor="name" value="Name" />

                  <TextInput
                    id="name"
                    name="name"
                    value={data.name}
                    className="mt-1 block w-full"
                    autoComplete="name"
                    isFocused={true}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                  />

                  <InputError message={errors.name} className="mt-2" />
                </div>


                <div>
                  <InputLabel htmlFor="last_name" value="Last Name" />

                  <TextInput
                    id="last_name"
                    name="last_name"
                    value={data.last_name}
                    className="mt-1 block w-full"
                    autoComplete="name"
                    isFocused={true}
                    onChange={(e) => setData('last_name', e.target.value)}
                    required
                  />

                  <InputError message={errors.last_name} className="mt-2" />
                </div>

                <div className="mt-4">
                  <div className="flex items-center gap-4 mt-2">
                    <label className="flex items-center">
                      <input
                        type="radio"
                        name="gender"
                        value="mr"
                        checked={data.gender === 'mr'}
                        onChange={(e) => setData('gender', e.target.value)}
                        className="mr-2"
                      />
                      Mr.
                    </label>

                    <label className="flex items-center">
                      <input
                        type="radio"
                        name="gender"
                        value="ms"
                        checked={data.gender === 'ms'}
                        onChange={(e) => setData('gender', e.target.value)}
                        className="mr-2"
                      />
                      Ms.
                    </label>
                  </div>

                  <InputError message={errors.gender} className="mt-2" />
                </div>

                <div className="mt-4">
                  <InputLabel htmlFor="email" value="Email" />

                  <TextInput
                    id="email"
                    type="email"
                    name="email"
                    value={data.email}
                    className="mt-1 block w-full"
                    autoComplete="username"
                    onChange={(e) => setData('email', e.target.value)}
                    required
                  />

                  <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                  <InputLabel htmlFor="password" value="Password" />

                  <TextInput
                    id="password"
                    type="password"
                    name="password"
                    value={data.password}
                    className="mt-1 block w-full"
                    autoComplete="new-password"
                    onChange={(e) => setData('password', e.target.value)}
                    required
                  />

                  <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4">
                  <InputLabel
                    htmlFor="password_confirmation"
                    value="Confirm Password"
                  />

                  <TextInput
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    value={data.password_confirmation}
                    className="mt-1 block w-full"
                    autoComplete="new-password"
                    onChange={(e) =>
                      setData('password_confirmation', e.target.value)
                    }
                    required
                  />


                  <InputError
                    message={errors.password_confirmation}
                    className="mt-2"
                  />
                </div>

                {/* ✅ Terms & Conditions Checkbox */}
                <div className="mt-4 flex items-center">
                  <Checkbox
                    id="terms"
                    name="terms"
                    checked={data.terms}
                    onChange={(e) => setData('terms', e.target.checked)}
                    className="mr-2 checkbox checkbox-sm"
                    required
                  />
                  <label htmlFor="terms" className="text-sm text-gray-700">
                    I agree to the{' '}
                    <a href={route('terms')} target="_blank"
                          rel="noopener noreferrer" className="text-blue-600 underline">
                      Terms and Conditions
                    </a>
                  </label>
                </div>
                <InputError message={errors.terms} className="mt-2" />


                <div className="mt-4 flex items-center justify-end">
                  <Link
                    href={route('login')}
                    className="link"
                  >
                    Already registered?
                  </Link>

                  <PrimaryButton className="ms-4" disabled={processing}>
                    Register
                  </PrimaryButton>
                </div>
              </form>
            </div>
          </div>



        </AuthenticatedLayout>
    );
}
