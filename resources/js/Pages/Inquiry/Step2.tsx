import { useState, useEffect } from "react";
import {useForm, router, Link} from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {getFromLocalStorage, saveToLocalStorage} from "@/utils/auth";
import {NumberInput} from "@/Components/Core/NumberInput";


interface Inquiry {
  id: string;
}

interface Step2Props {
  inquiry: Inquiry;
}

export default function Step2({ inquiry }: Step2Props) {
  const [step, setStep] = useState(1);

  // Load saved data from localStorage
  //const savedData = getFromLocalStorage("inquiry_form_step_2") || {};
  const savedData = JSON.parse(localStorage.getItem("inquiry_form_step_2") || "{}");

  const { data, setData, post, processing } = useForm({
    current_home_type: savedData.current_home_type || "",
    floor: savedData.floor || "",
    rooms: savedData.rooms || "",
    square_meters: savedData.square_meters || "0",
    has_elevator: savedData.has_elevator || "No",
    long_distance: savedData.long_distance || false,
    distance_meters: savedData.distance_meters || "",
    has_steps: savedData.has_steps || false,
    num_steps: savedData.num_steps || "",
    impeded: savedData.impeded || false,
    impeded_details: savedData.impeded_details || "",
  });

  // Save form data to localStorage whenever it changes
  // useEffect(() => {
  //   saveToLocalStorage("inquiry_form_step_2", data);
  // }, [data]);


  useEffect(() => {
    localStorage.setItem("inquiry_form_step_2", JSON.stringify(data));
  }, [data]);


  function submit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    post(route("inquiry.step2.store", { inquiry: inquiry.id }));
  }


  return (
    <AuthenticatedLayout>

      <div className="min-h-[calc(100vh-64px)] flex items-start">
        <div className="bg-white px-10 py-10 shadow-md sm:rounded-lg w-[500px] mt-6 mx-auto">

          <div>
            {step === 1 &&
              <div  className='text-center'>
                <h1 className="text-3xl font-bold ">Current Home</h1>
                <p className="">Following we will ask you detailed questions about your current home.</p>
                <Link href="#" className="text-sm text-gray-600">Why do we need this information?</Link>
              </div>
            }{step === 2 &&
            <div  className='text-center'>
              <p className="text-sm">Current Home</p>
              <h1 className="text-3xl font-bold ">Floors</h1>
              <Link href="#" className="text-sm text-gray-600">Why do we need this information?</Link>
            </div>
          }{step === 3 &&
            <div  className='text-center'>
              <p className="text-sm">Current Home</p>
              <h1 className="text-3xl font-bold ">Size</h1>
              <Link href="#" className="text-sm text-gray-600">Why do we need this information?</Link>
            </div>
          }{step === 4 &&
            <div  className='text-center'>
              <p className="text-sm">Current Home</p>
              <h1 className="text-3xl font-bold ">Access</h1>
              <Link href="#" className="text-sm text-gray-600">Why do we need this information?</Link>
            </div>
          }{step === 5 &&
            <div  className='text-center'>
              <p className="text-sm">Accessibility</p>
              <h1 className="text-3xl font-bold ">Access</h1>
              <Link href="#" className="text-sm text-gray-600">Why do we need this information?</Link>
            </div>
          }
          </div>


          <form onSubmit={submit}>
            {/* Step 1: Select Home Type */}
            {step === 1 && (
              <div  className="mt-6">
                <h2 className="text-lg font-semibold">Where are you moving out of?</h2>
                <div className="grid grid-cols-2 gap-3 mt-4">
                  {["House", "Apartment", "Shared Flat", "Storage", "Office"].map((type) => (
                    <button
                      key={type}
                      onClick={() => setData("current_home_type", type)}
                      type="button"
                      className={`p-3 rounded-lg border ${
                        data.current_home_type === type ? "bg-neutral text-white" : "btn-white"
                      }`}
                    >
                      {type}
                    </button>
                  ))}
                </div>

                <div className="flex justify-between mt-6">
                  <button type="button" onClick={() => router.visit(route("inquiry.start"))} className="btn ">
                    Back
                  </button>
                  <button type="button"  onClick={() => setStep(2)} className="btn btn-neutral">
                    Continue
                  </button>
                </div>
              </div>
            )}

            {/* Step 2: Select Floor */}
            {step === 2 &&  (
              <div className="mt-6">
                <h2 className="text-lg font-semibold">Select Floor</h2>
                <div className="grid grid-cols-2 gap-3 mt-4">
                  {["Basement", "Ground Floor", "Mezzanine Floor", "1", "2", "3", "4", "5+"].map((floor) => (
                    <button
                      key={floor}
                      onClick={() => setData("floor", floor)}
                      type="button"
                      className={`p-3 rounded-lg border ${
                        data.floor === floor ? "bg-neutral text-white" : "btn-white"
                      }`}
                    >
                      {floor}
                    </button>
                  ))}
                </div>

                <div className="flex justify-between mt-6">
                  <button type="button" onClick={() => setStep(1)} className="btn ">
                    Back
                  </button>
                  <button type="button"  onClick={() => setStep(3)} className="btn btn-neutral">
                    Continue
                  </button>
                </div>
              </div>
            )}

            {/*{step === 2 && data.current_home_type == "House" && (*/}
            {/*  <div className="mt-6">*/}
            {/*    <div className="mt-6 mb-20 flex flex-col  items-center">*/}
            {/*      <label className='label'><span className='label-text font-medium text-lg'>How many floors does your house have?</span></label>*/}
            {/*      <NumberInput*/}
            {/*        initialValue={5}*/}
            {/*        value={data.rooms ?? ""}*/}
            {/*        min={0}*/}
            {/*        max={5}*/}
            {/*        step={1}*/}
            {/*        className={`w-full`}*/}
            {/*      />*/}

            {/*    </div>*/}

            {/*    <div className="flex justify-between mt-6">*/}
            {/*      <button type="button" onClick={() => setStep(1)} className="btn ">*/}
            {/*        Back*/}
            {/*      </button>*/}
            {/*      <button type="button"  onClick={() => setStep(3)} className="btn btn-neutral">*/}
            {/*        Continue*/}
            {/*      </button>*/}
            {/*    </div>*/}


            {/*  </div>*/}
            {/*)}*/}


            {/* Step 3: Home Size */}
            {step === 3 && (
              <div className="mt-6">
                <h2 className="text-lg font-semibold mt-6 mb-2">Home Size</h2>
                <div className="flex gap-20">
                  <div className="flex-1">
                    <label className='label'><span className='label-text font-medium text-sm'>Rooms</span></label>
                    <NumberInput
                      className='input'
                      type="number"
                      value={data.rooms}
                      onChange={(e) => setData("rooms", e.target.value)}
                      min={1}
                      max={8}
                      step={0.5}
                    />
                  </div>
                  <div className="flex-1">
                    <label className='label '><span className='label-text font-medium text-sm'>Square Meters</span></label>
                    <NumberInput
                      className='input'
                      type="number"
                      value={data.square_meters}
                      onChange={(e) => setData("square_meters", e.target.value)}
                      min={0}
                      max={300}
                      step={10}
                    />
                  </div>
                </div>
                <div className="flex justify-between mt-12">
                  <button type="button" onClick={() => setStep(2)} className="btn ">
                    Back
                  </button>
                  <button type="button"  onClick={() => setStep(4)} className="btn btn-neutral">
                    Continue
                  </button>
                </div>
              </div>
            )}

            {/* Step 4: Is there a lift? */}
            {step === 4 && (
              <div className="mt-6">
                <h2 className="text-lg font-semibold">Is there a lift?</h2>
                <div className="grid grid-cols-2 gap-3 mt-4">
                  {["No", "Yes, for 2-3 people", "Yes, for 4-5 people", "Yes, for 6+ people", "Yes, goods lift for 10+ people"].map((option) => (
                    <button
                      key={option}
                      onClick={() => setData("has_elevator", option)}
                      type="button"
                      className={`p-3 rounded-lg border ${
                        data.has_elevator === option ? "bg-neutral text-white" : "btn-white"
                      }`}
                    >
                      {option}
                    </button>
                  ))}
                </div>

                <div className="flex justify-between mt-6">
                  <button type="button" onClick={() => setStep(3)} className="btn ">
                    Back
                  </button>
                  <button type="button"  onClick={() => setStep(5)} className="btn btn-neutral">
                    Continue
                  </button>
                </div>
              </div>
            )}

            {/* Step 5: Submit */}
            {step === 5 && (
              <div  className="mt-6">
                <h2 className="text-lg font-semibold">The path from parking to building entrance...</h2>

                {/* Checkbox: Longer than 5 meters */}
                <div className="mt-6">
                  <label className="flex gap-3 items-center">
                    <input
                      type="checkbox"
                      className="checkbox checkbox-sm"
                      checked={data.long_distance}
                      onChange={(e) => setData("long_distance", e.target.checked)}
                    />
                    ... is longer than 5 meters
                  </label>

                  {/* Show input field only if checked */}
                  {data.long_distance && (
                    <div className="mt-2 ml-6 w-2/4">
                      <label className="label">
                        <span className="label-text font-medium text-sm">Distance in meters</span>
                      </label>
                      <NumberInput
                        className='input'
                        type="number"
                        value={data.distance_meters}
                        onChange={(e) => setData("distance_meters", e.target.value)}
                        min={0}
                        max={200}
                        step={10}
                      />
                    </div>
                  )}
                </div>

                {/* Checkbox: Has Steps */}
                <div className="mt-6">
                  <label className="flex gap-3 items-center">
                    <input
                      type="checkbox"
                      className="checkbox checkbox-sm"
                      checked={data.has_steps}
                      onChange={(e) => setData("has_steps", e.target.checked)}
                    />
                    ... has steps
                  </label>

                  {/* Show input field only if checked */}
                  {data.has_steps && (
                    <div className="mt-2 ml-6 w-2/4">
                      <label className="label">
                        <span className="label-text font-medium text-sm">Number of steps</span>
                      </label>
                      <NumberInput
                        className='input'
                        type="number"
                        value={data.num_steps}
                        onChange={(e) => setData("num_steps", e.target.value)}
                        min={0}
                        max={40}
                        step={5}
                      />
                    </div>
                  )}
                </div>

                {/* Checkbox: Otherwise impeded */}
                <div className="mt-6">
                  <label className="flex gap-3 items-center">
                    <input
                      type="checkbox"
                      className="checkbox checkbox-sm"
                      checked={data.impeded}
                      onChange={(e) => setData("impeded", e.target.checked)}
                    />
                    ... is otherwise impeded (e.g. maisonette)
                  </label>

                  {/* Show textarea only if checked */}
                  {data.impeded && (
                    <div className="mt-6 ml-6">
                      <textarea
                        placeholder="Please describe your situation..."
                        value={data.impeded_details}
                        onChange={(e) => setData("impeded_details", e.target.value)}
                        className="!textarea !textarea-bordered !textarea-sm w-full h-32"
                      ></textarea>
                    </div>
                  )}
                </div>

                <div className="flex justify-between mt-6">
                  <button type="button" onClick={() => setStep(4)} className="btn ">
                    Back
                  </button>
                  {/*<button type="submit" disabled={processing} className="btn btn-neutral">*/}
                  {/*  {processing ? "Saving..." : "Submit"}*/}
                  {/*</button>*/}

                  <button className='btn btn-neutral' type="submit" disabled={processing}>Continue</button>
                </div>
              </div>
            )}

          </form>
        </div>
      </div>






    </AuthenticatedLayout>
  );
}
